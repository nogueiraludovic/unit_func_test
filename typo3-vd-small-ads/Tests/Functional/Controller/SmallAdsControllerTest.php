<?php

declare(strict_types=1);

namespace Vd\VdSmallAds\Tests\Functional\Controller;

use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use Vd\VdSmallAds\Controller\SmallAdsController;
use Vd\VdSmallAds\DataTransferObject\Demand;
use Vd\VdSmallAds\Domain\Repository\SmallAdsRepository;
use Vd\VdSmallAds\Session\SessionStorage;

final class SmallAdsControllerTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['vd/vd-small-ads'];

    // -------------------------------------------------------------------------
    // createDemand()
    // -------------------------------------------------------------------------

    #[Test]
    public function createDemandReturnsNullWhenNoDemandAndNoSession(): void
    {
        $sessionStorage = $this->createMock(SessionStorage::class);
        $sessionStorage->expects(self::once())->method('has')->with('tx_test')->willReturn(false);

        $controller = $this->buildController([''], $sessionStorage);
        $controller->_set('sessionIdentifier', 'tx_test');

        $this->assertNull($controller->_call('createDemand', null));
    }

    #[Test]
    public function createDemandReturnsFromSessionWhenNoDemandButSessionHasValue(): void
    {
        $demand = new Demand('sale', 'car', 'query');

        $sessionStorage = $this->createMock(SessionStorage::class);
        $sessionStorage->expects(self::once())->method('has')->with('tx_test')->willReturn(true);
        $sessionStorage->expects(self::once())->method('getDemand')->with('tx_test')->willReturn($demand);

        $controller = $this->buildController([''], $sessionStorage);
        $controller->_set('sessionIdentifier', 'tx_test');

        $this->assertSame($demand, $controller->_call('createDemand', null));
    }

    #[Test]
    public function createDemandStoresInSessionAndReturnsDemandWhenDemandIsProvided(): void
    {
        $demand = new Demand('sale', 'car', 'query');

        $sessionStorage = $this->createMock(SessionStorage::class);
        $sessionStorage->expects(self::never())->method('has');
        $sessionStorage->expects(self::once())->method('set')->with('tx_test', (string)$demand);

        $controller = $this->buildController([''], $sessionStorage);
        $controller->_set('sessionIdentifier', 'tx_test');

        $this->assertSame($demand, $controller->_call('createDemand', $demand));
    }

    // -------------------------------------------------------------------------
    // getPage()
    // -------------------------------------------------------------------------

    #[Test]
    public function getPageReturnsOneWhenNoPageArgumentPresent(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('hasArgument')->with('page')->willReturn(false);

        $controller = $this->buildController([]);
        $controller->_set('request', $request);

        $this->assertSame(1, $controller->_call('getPage'));
    }

    #[Test]
    public function getPageReturnsPageArgumentWhenPresent(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('hasArgument')->with('page')->willReturn(true);
        $request->method('getArgument')->with('page')->willReturn('4');

        $controller = $this->buildController([]);
        $controller->_set('request', $request);

        $this->assertSame(4, $controller->_call('getPage'));
    }

    #[Test]
    public function getPageReturnsMinimumOneWhenPageArgumentIsZeroOrNegative(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('hasArgument')->with('page')->willReturn(true);
        $request->method('getArgument')->with('page')->willReturn('-2');

        $controller = $this->buildController([]);
        $controller->_set('request', $request);

        $this->assertSame(1, $controller->_call('getPage'));
    }

    // -------------------------------------------------------------------------
    // listAction()
    // -------------------------------------------------------------------------

    #[Test]
    public function listActionAssignsRequiredVariablesToViewAndReturnsResponse(): void
    {
        $demand = new Demand('sale', 'car', 'query');
        $adTypes = [['name' => 'sale']];
        $objectTypes = [['name' => 'car']];

        $smallAds = $this->createMock(QueryResultInterface::class);
        $smallAds->method('count')->willReturn(0);
        $smallAds->method('toArray')->willReturn([]);

        $repository = $this->createMock(SmallAdsRepository::class);
        $repository->method('findDemanded')->with($demand)->willReturn($smallAds);
        $repository->method('findAdTypes')->willReturn($adTypes);
        $repository->method('findObjectTypes')->willReturn($objectTypes);

        $sessionStorage = $this->createMock(SessionStorage::class);

        $view = $this->createMock(ViewInterface::class);
        $view->expects(self::once())
            ->method('assignMultiple')
            ->with(self::callback(
                static fn(array $vars): bool => array_key_exists('adTypes', $vars)
                    && array_key_exists('demand', $vars)
                    && array_key_exists('objectTypes', $vars)
                    && array_key_exists('pagination', $vars)
                    && array_key_exists('paginator', $vars)
                    && array_key_exists('smallAds', $vars)
            ));

        $response = $this->createMock(ResponseInterface::class);

        $request = $this->createMock(Request::class);
        $request->method('hasArgument')->willReturn(false);

        $controller = $this->buildController(['htmlResponse'], $sessionStorage, $repository);
        $controller->method('htmlResponse')->willReturn($response);
        $controller->_set('view', $view);
        $controller->_set('request', $request);

        $result = $controller->listAction($demand);

        $this->assertSame($response, $result);
    }

    #[Test]
    public function listActionWithNullDemandAndNoSessionReturnsResponse(): void
    {
        $smallAds = $this->createMock(QueryResultInterface::class);
        $smallAds->method('count')->willReturn(0);
        $smallAds->method('toArray')->willReturn([]);

        $repository = $this->createMock(SmallAdsRepository::class);
        $repository->method('findDemanded')->with(null)->willReturn($smallAds);
        $repository->method('findAdTypes')->willReturn([]);
        $repository->method('findObjectTypes')->willReturn([]);

        $sessionStorage = $this->createMock(SessionStorage::class);
        $sessionStorage->method('has')->willReturn(false);

        $view = $this->createMock(ViewInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $request = $this->createMock(Request::class);
        $request->method('hasArgument')->willReturn(false);

        $controller = $this->buildController(['htmlResponse'], $sessionStorage, $repository);
        $controller->method('htmlResponse')->willReturn($response);
        $controller->_set('view', $view);
        $controller->_set('request', $request);
        $controller->_set('sessionIdentifier', 'tx_test');

        $result = $controller->listAction(null);

        $this->assertSame($response, $result);
    }

    // -------------------------------------------------------------------------
    // resetAction()
    // -------------------------------------------------------------------------

    #[Test]
    public function resetActionRemovesSessionKeyAndReturnsRedirectResponse(): void
    {
        $sessionStorage = $this->createMock(SessionStorage::class);
        $sessionStorage->expects(self::once())->method('remove')->with('tx_test');

        $response = $this->createMock(ResponseInterface::class);

        $controller = $this->buildController(['redirect'], $sessionStorage);
        $controller->method('redirect')
            ->with('list', null, null, ['demand' => null])
            ->willReturn($response);
        $controller->_set('sessionIdentifier', 'tx_test');

        $result = $controller->resetAction();

        $this->assertSame($response, $result);
    }

    // -------------------------------------------------------------------------
    // helpers
    // -------------------------------------------------------------------------

    /**
     * @param list<string> $mockedMethods
     */
    private function buildController(
        array $mockedMethods,
        ?SessionStorage $sessionStorage = null,
        ?SmallAdsRepository $repository = null
    ): SmallAdsController {
        return $this->getAccessibleMock(
            SmallAdsController::class,
            $mockedMethods,
            [
                $sessionStorage ?? $this->createMock(SessionStorage::class),
                $repository ?? $this->createMock(SmallAdsRepository::class),
            ]
        );
    }
}
