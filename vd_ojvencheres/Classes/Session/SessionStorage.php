<?php

declare(strict_types=1);

namespace Vd\VdOjvencheres\Session;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use Vd\VdOjvencheres\Domain\Model\Dto\ItemDemand;
use Vd\VdOjvencheres\Domain\Model\Dto\SaleDemand;

use function array_key_exists;
use function array_merge;
use function is_array;
use function unserialize;

class SessionStorage implements SingletonInterface
{
    protected TypoScriptFrontendController $frontendController;
    protected string $identifier = '';

    public function __construct()
    {
        $this->frontendController = $this->getFrontendController();
        $this->identifier = 'tx_vdojvencheres_' . $this->frontendController->id;
    }

    public function get(): array
    {
        $data = $this->frontendController->fe_user->getKey('ses', $this->identifier);

        return is_array($data) === true ? $data : [];
    }

    public function getDemand()
    {
        $data = $this->get();

        if (isset($data['demand']) === false) {
            return null;
        }

        $demand = @unserialize(
            (string)$data['demand'],
            [
                'allowed_classes' => [
                    ItemDemand::class,
                    SaleDemand::class
                ]
            ]
        );

        return (($demand instanceof ItemDemand) === true || ($demand instanceof SaleDemand) === true) ? $demand : null;
    }

    public function getSerializedKey(string $key): array
    {
        $data = $this->get();
        $dataUnserialized = @unserialize((string)($data[$key] ?? null), ['allowed_classes' => []]);

        return is_array($dataUnserialized) === true ? $dataUnserialized : [];
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->get());
    }

    public function push(array $data): void
    {
        $sessionData = array_merge($this->get(), $data);

        $this->frontendController->fe_user->setAndSaveSessionData($this->identifier, $sessionData);
    }

    public function reset(): void
    {
        $this->frontendController->fe_user->setAndSaveSessionData($this->identifier, null);
    }

    protected function getFrontendController(): TyposcriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
