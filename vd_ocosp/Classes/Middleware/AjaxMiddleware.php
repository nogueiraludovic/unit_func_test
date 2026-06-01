<?php

declare(strict_types=1);

namespace Vd\VdOcosp\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_filter;
use function array_map;
use function array_unique;
use function count;

class AjaxMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getUri()->getPath() !== '/ocosp/ajax') {
            return $handler->handle($request);
        }

        $data['count'] = $this->countProfessionsByInterests($request);

        return new JsonResponse($data);
    }

    protected function countProfessionsByInterests(ServerRequestInterface $request): int
    {
        $queryParams = $request->getQueryParams();
        $queryParams = $queryParams['tx_vdocosp'] ?? [];

        $interests = array_unique(array_filter(array_map('intval', $queryParams['interests'] ?? [])));
        $interestsCount = count($interests);

        if ($interestsCount === 0) {
            return 0;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_vdocosp_dmde');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        $records = $queryBuilder
            ->count('tx_vdocosp_dmde.uid')
            ->from('tx_vdocosp_dmde')
            ->join(
                'tx_vdocosp_dmde',
                'tx_vdocosp_dmde_interet_mm',
                'tx_vdocosp_dmde_interet_mm',
                $queryBuilder->expr()->eq(
                    'tx_vdocosp_dmde_interet_mm.uid_local',
                    $queryBuilder->quoteIdentifier('tx_vdocosp_dmde.uid')
                )
            )
            ->join(
                'tx_vdocosp_dmde_interet_mm',
                'tx_vdocosp_interets',
                'tx_vdocosp_interets',
                $queryBuilder->expr()->eq(
                    'tx_vdocosp_dmde_interet_mm.uid_foreign',
                    $queryBuilder->quoteIdentifier('tx_vdocosp_interets.uid')
                )
            )
            ->add('groupBy', '`tx_vdocosp_dmde`.`uid` HAVING COUNT(`tx_vdocosp_dmde`.`uid`) >=' . $interestsCount)
            ->where(
                $queryBuilder->expr()->in(
                    'tx_vdocosp_dmde_interet_mm.uid_foreign',
                    $queryBuilder->createNamedParameter($interests, Connection::PARAM_INT_ARRAY)
                )
            )
            ->execute()
            ->fetchAllAssociative();

        return count($records ?: []);
    }
}
