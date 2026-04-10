<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Command;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Resource\DuplicationBehavior;
use TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use Vd\VdSmallAds\Domain\Model\SmallAds;
use Vd\VdSmallAds\Domain\Repository\SmallAdsRepository;

use function base64_decode;
use function explode;
use function fclose;
use function file_exists;
use function filter_var;
use function fopen;
use function fwrite;
use function getenv;
use function is_dir;
use function json_decode;
use function mime_content_type;
use function rtrim;
use function sha1_file;
use function time;

use const FILTER_VALIDATE_URL;
use const JSON_THROW_ON_ERROR;

class ImportCommand extends Command
{
    protected int $fileStorageUid = 1;
    protected int $storagePid = 0;
    protected string $uploadFolder = 'user_upload/intranet/Petites_annonces/Images/';
    protected string $uploadPath = '';

    public function __construct(
        protected readonly CacheManager $cacheManager,
        protected readonly ConnectionPool $connection,
        protected readonly PersistenceManager $persistenceManager,
        protected readonly SmallAdsRepository $smallAdsRepository,
        protected readonly StorageRepository $storageRepository
    ) {
        parent::__construct();
    }

    protected function base64ToImage(string $input, string $output): void
    {
        $data = explode(',', $input);
        $file = fopen($output, 'wb');

        fwrite($file, base64_decode($data[1]));
        fclose($file);
    }

    protected function checkResourceExistsInStorage(string $filePath): int
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('sys_file');
        $queryBuilder->getRestrictions()->removeAll();

        return (int)$queryBuilder
            ->select('uid')
            ->from('sys_file')
            ->where(
                $queryBuilder->expr()->eq('sha1', $queryBuilder->createNamedParameter(sha1_file($filePath))),
                $queryBuilder->expr()->eq(
                    'storage',
                    $queryBuilder->createNamedParameter($this->fileStorageUid, Connection::PARAM_INT)
                )
            )
            ->executeQuery()
            ->fetchOne();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('fileStorageUid', InputArgument::REQUIRED, 'File storage uid')
            ->addArgument('storagePid', InputArgument::REQUIRED, 'Storage pid')
            ->addArgument('uploadFolder', InputArgument::REQUIRED, 'Images upload folder')
            ->setDescription('Imports small ads via a webservice.');
    }

    protected function copyImagesToFileSystem(array $images): void
    {
        $storage = $this->storageRepository->getStorageObject($this->fileStorageUid);

        foreach ($images as $image) {
            $base64encoded = (string)($image['base64'] ?? '');

            if ($base64encoded === '' || $base64encoded === '0') {
                continue;
            }

            $mimeType = explode('/', mime_content_type($base64encoded));
            $fileExtension = $mimeType[1];

            if ($fileExtension === 'jpeg') {
                $fileExtension = 'jpg';
            }

            $filename = 'original-ad-uid-' . $image['vdexternaluid'] . '-file.' . $fileExtension;
            $temporaryPath = Environment::getVarPath() . '/transient/' . $filename;

            $this->base64ToImage($base64encoded, $temporaryPath);

            $filePath = $this->uploadPath . $filename;
            $oldSysFileUid = 0;

            if (file_exists($filePath) === true) {
                $oldSysFileUid = $this->checkResourceExistsInStorage($filePath);
            }

            if ($oldSysFileUid > 0) {
                $queryBuilder = $this->connection->getQueryBuilderForTable('sys_file_reference');
                $queryBuilder
                    ->delete('sys_file_reference')
                    ->where(
                        $queryBuilder->expr()->eq('fieldname', $queryBuilder->createNamedParameter('image')),
                        $queryBuilder->expr()->eq(
                            'tablenames',
                            $queryBuilder->createNamedParameter('tx_vdsmallads_domain_model_smallads')
                        ),
                        $queryBuilder->expr()->eq(
                            'uid_local',
                            $queryBuilder->createNamedParameter($oldSysFileUid, Connection::PARAM_INT)
                        )
                    )
                    ->executeStatement();
                $sysFileUid = $oldSysFileUid;
            } else {
                try {
                    $newFile = $storage->addFile(
                        $temporaryPath,
                        $storage->getFolder($this->uploadFolder),
                        $filename,
                        DuplicationBehavior::REPLACE
                    );
                    $sysFileUid = 0;

                    if ($newFile !== null) {
                        $sysFileUid = $newFile->getUid();
                    }
                } catch (ExistingTargetFileNameException) {
                    $sysFileUid = 0;
                }
            }

            if ($sysFileUid > 0) {
                $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdsmallads_domain_model_smallads');

                $uid = (int)$queryBuilder
                    ->select('uid')
                    ->from('tx_vdsmallads_domain_model_smallads')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'vdexternaluid',
                            $queryBuilder->createNamedParameter($image['vdexternaluid'], Connection::PARAM_INT)
                        )
                    )
                    ->executeQuery()
                    ->fetchOne();

                $queryBuilder = $this->connection->getQueryBuilderForTable('sys_file');
                $queryBuilder
                    ->update('sys_file')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($sysFileUid, Connection::PARAM_INT)
                        )
                    )
                    ->set('identifier', '/' . $this->uploadFolder . $filename)
                    ->set('storage', $this->fileStorageUid)
                    ->executeStatement();

                $queryBuilder = $this->connection->getQueryBuilderForTable('sys_file_reference');
                $queryBuilder
                    ->insert('sys_file_reference')
                    ->values([
                        'crdate' => time(),
                        'fieldname' => 'image',
                        'pid' => $this->storagePid,
                        'sorting_foreign' => 128,
                        'tablenames' => 'tx_vdsmallads_domain_model_smallads',
                        'table_local' => 'sys_file',
                        'tstamp' => time(),
                        'uid_foreign' => $uid,
                        'uid_local' => $sysFileUid
                    ])
                    ->executeStatement();

                $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdsmallads_domain_model_smallads');
                $queryBuilder
                    ->update('tx_vdsmallads_domain_model_smallads')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)
                        )
                    )
                    ->set('image', 1)
                    ->executeStatement();
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $webserviceUrl = getenv('TYPO3_VDSMALLADS_SERVICE_URL');

        if ($webserviceUrl === false) {
            throw new RuntimeException('The webservice URL is not configured.', 1651242749);
        }

        if (filter_var($webserviceUrl, FILTER_VALIDATE_URL) === false) {
            throw new RuntimeException('The webservice URL is not a valid URL.', 1705654833);
        }

        $data = GeneralUtility::getUrl($webserviceUrl);

        if ($data === false) {
            throw new RuntimeException(
                'There was a problem contacting the server: ' . $webserviceUrl . '.',
                1705654845
            );
        }

        $this->fileStorageUid = (int)($input->getArgument('fileStorageUid') ?? 1);
        $this->storagePid = (int)($input->getArgument('storagePid') ?? 0);
        $this->uploadFolder = rtrim((string)$input->getArgument('uploadFolder'), '/') . '/'
            ?? 'user_upload/intranet/Petites_annonces/Images/';
        $this->uploadPath = Environment::getPublicPath()
            . '/'
            . (rtrim($GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir'] ?? 'fileadmin', '/') . '/')
            . $this->uploadFolder;

        if ($data !== '') {
            $this->importData($data);
        }

        return Command::SUCCESS;
    }

    protected function flushCache(): void
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tt_content');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $query = $queryBuilder
            ->selectLiteral('DISTINCT `pid`')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('list')),
                $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter('vdsmallads_pi1'))
            )
            ->executeQuery();

        $plugins = [];

        while ($row = $query->fetchAssociative()) {
            $plugins[] = $row['pid'];
        }

        if ($plugins === []) {
            return;
        }

        $tags = [];

        foreach ($plugins as $plugin) {
            $tags[] = 'pageId_' . $plugin;
        }

        $this->cacheManager->flushCachesInGroupByTags('pages', $tags);
    }

    protected function importData(string $data): void
    {
        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        if ($data === null) {
            return;
        }

        $this->smallAdsRepository->removeAll();

        foreach ($data as $properties) {
            $uid = (int)($properties['uid'] ?? 0);

            $smallAd = GeneralUtility::makeInstance(SmallAds::class)
                ->setCat((string)($properties['cat'] ?? ''))
                ->setCat2((string)($properties['cat2'] ?? ''))
                ->setComment((string)($properties['comment'] ?? ''))
                ->setContent((string)($properties['content'] ?? ''))
                ->setCrdateexternal((int)($properties['crdate'] ?? 0))
                ->setDisplayemail((bool)($properties['displayemail'] ?? false))
                ->setEmail((string)($properties['email'] ?? ''));

            $image = [
                'base64' => $properties['image'],
                'vdexternaluid' => $uid
            ];

            if ($image['base64'] !== 0 && $image['base64'] !== '') {
                $images[] = $image;
            }

            $smallAd
                ->setIscommercial((bool)($properties['iscommercial'] ?? false))
                ->setPhone((string)($properties['phone'] ?? ''));
            $smallAd->setPid($this->storagePid ?? 0);

            $this->smallAdsRepository->add(
                $smallAd
                    ->setReviewed((bool)($properties['reviewed'] ?? false))
                    ->setSlug((string)($properties['slug'] ?? ''))
                    ->setTitle((string)($properties['title'] ?? ''))
                    ->setUser((string)($properties['user'] ?? ''))
                    ->setVdexternaluid($uid)
            );
        }

        $this->persistenceManager->persistAll();

        if (is_dir($this->uploadPath) === false) {
            try {
                GeneralUtility::mkdir_deep($this->uploadPath);
            } catch (RuntimeException) {
            }
        }

        $this->copyImagesToFileSystem($images ?? []);
        $this->flushCache();
    }
}
