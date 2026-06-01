<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Tests\Functional\Persistence;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use Vd\VdPrestations\Persistence\XmlDataMapper;

use function file_get_contents;

final class XmlDataMapperTest extends FunctionalTestCase
{
    private const XML_PRESTATION_ADD_EXAMPLE = 'Resources/Private/Tests/prestation_validation_add.xml';
    private const XML_PRESTATION_DELETE_EXAMPLE = 'Resources/Private/Tests/prestation_validation_delete.xml';
    private const XML_PRESTATION_EDIT_EXAMPLE = 'Resources/Private/Tests/prestation_validation_edit.xml';

    protected ConnectionPool $connection;
    protected XmlDataMapper $subject;
    protected $testExtensionsToLoad = ['typo3conf/ext/vd_prestations'];

    /**
     * @test
     */
    public function consumeTest(): void
    {
        self::assertSame($this->countPrestation(), 0);

        $this->subject->consume(
            file_get_contents(ExtensionManagementUtility::extPath('vd_prestations') . self::XML_PRESTATION_ADD_EXAMPLE)
        );

        self::assertSame($this->countPrestation(), 1);

        $this->subject->consume(
            file_get_contents(ExtensionManagementUtility::extPath('vd_prestations') . self::XML_PRESTATION_EDIT_EXAMPLE)
        );

        self::assertSame($this->countPrestation(), 1);
        self::assertSame(
            $this->getPrestation()['result'],
            'Signalement d\'une mineure en danger dans son développement'
        );
        self::assertSame((int)$this->getPrestation()['hidden'], 0);

        $this->subject->consume(
            file_get_contents(
                ExtensionManagementUtility::extPath('vd_prestations') . self::XML_PRESTATION_DELETE_EXAMPLE
            )
        );

        self::assertSame((int)$this->getPrestation()['hidden'], 1);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class);
        $this->subject = GeneralUtility::makeInstance(XmlDataMapper::class, false);
    }

    private function countPrestation(): int
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdprestations_domain_model_prestation');
        $queryBuilder->getRestrictions()->removeAll();

        $query = $queryBuilder
            ->count('*')
            ->from('tx_vdprestations_domain_model_prestation')
            ->where($queryBuilder->expr()->eq('title', $queryBuilder->createNamedParameter('Signaler un mineur en danger dans son développement')));

        return (int)$query->execute()->fetchOne();
    }

    private function getPrestation(): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdprestations_domain_model_prestation');
        $queryBuilder->getRestrictions()->removeAll();

        $query = $queryBuilder
            ->select('*')
            ->from('tx_vdprestations_domain_model_prestation')
            ->where($queryBuilder->expr()->eq('title', $queryBuilder->createNamedParameter('Signaler un mineur en danger dans son développement')));

        return $query->execute()->fetchAssociative();
    }
}
