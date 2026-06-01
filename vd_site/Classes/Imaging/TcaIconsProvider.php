<?php

declare(strict_types=1);

namespace Vd\VdSite\Imaging;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function is_array;
use function pathinfo;
use function rtrim;
use function str_replace;

use const PATHINFO_FILENAME;

class TcaIconsProvider implements SingletonInterface
{
    protected array $icons = [];

    public function addIconsCollections(): void
    {
        $iconsCollectionsConfiguration = $this->getIconsCollectionsConfiguration();

        foreach ($iconsCollectionsConfiguration as $iconsCollectionConfiguration) {
            $iconsCollection = $this->getIconsCollection($iconsCollectionConfiguration);

            foreach ($iconsCollection as $icon) {
                $this->addIcon(
                    $icon,
                    (string)$iconsCollectionConfiguration['style'],
                    (string)$iconsCollectionConfiguration['prefix']
                );
            }
        }
    }

    public function getIcons(): array
    {
        return $this->icons;
    }

    protected function addIcon(string $name, string $style, string $prefix = ''): void
    {
        $identifier = pathinfo($name, PATHINFO_FILENAME);

        if ($prefix === '') {
            $prefix = $style;
        }

        $this->icons[] = [
            'css' => $style . ' ' . $prefix . '-' . $identifier,
            'identifier' => $style . '-' . $identifier,
            'name' => str_replace('-', ' ', $identifier)
        ];
    }

    protected function getIconsCollection(array $configuration): array
    {
        $icons = GeneralUtility::getFilesInDir(
            $this->getIconsPath($configuration),
            'svg',
            false,
            '',
            (string)$configuration['excludePattern']
        );

        return is_array($icons) === false ? [] : $icons;
    }

    protected function getIconsCollectionsConfiguration(): array
    {
        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('vd_site', __CLASS__);

        return $configuration['iconsCollections'] ?? [];
    }

    protected function getIconsPath(array $configuration): string
    {
        return ExtensionManagementUtility::extPath(
            (string)$configuration['extensionKey'],
            rtrim((string)$configuration['path'], '/') . '/'
        );
    }
}
