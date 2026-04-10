<?php

declare(strict_types=1);

namespace Vd\VdWebservice\Query;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdWebservice\Imaging\ImageConverter;

final readonly class SmallAdsQuery
{
    public function __construct(private ConnectionPool $connection, private ImageConverter $imageConverter)
    {
    }

    public function fetchAll(): array
    {
        $queryBuilder = $this->connection->getQueryBuilderForTable('tx_vdsmallads_domain_model_smallads');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $statement = $queryBuilder
            ->select(
                'cat',
                'cat2',
                'content',
                'crdateexternal',
                'email',
                'image',
                'iscommercial',
                'phone',
                'reviewed',
                'slug',
                'title',
                'uid',
                'user'
            )
            ->from('tx_vdsmallads_domain_model_smallads')
            ->executeQuery();

        $records = [];

        while ($row = $statement->fetchAssociative()) {
            if ((int)($row['image'] ?? 0) !== 0) {
                $image = $this->imageConverter->base64Image(
                    'image',
                    'tx_vdsmallads_domain_model_smallads',
                    $row['uid']
                );

                if ($image !== '') {
                    $row['image'] = $image;
                }
            }

            $records[] = $row;
        }

        return $records;
    }
}
