<?php

declare(strict_types=1);

namespace Vd\VdClimatePolicy\EventListener;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdClimatePolicy\Database\RecordRepository;
use Vd\VdFrontend\Event\AfterInitializeActionEvent;

use function is_file;
use function md5;
use function str_replace;
use function strpos;
use function strtolower;

final class AssetModifier
{
    public function __invoke(AfterInitializeActionEvent $event): void
    {
        if ($event->getPage()['uid'] !== 1016212) {
            return;
        }

        $assetCollector = $event->getAssetCollector();
        $cssForColor = $this->getCssForColor();

        if ($cssForColor !== '') {
            // @extensionScannerIgnoreLine
            $assetCollector->addStyleSheet('vd-climate-policy', $this->writeStyleSheetContentToTemporaryFile($cssForColor));
        }

//        $assetCollector->addJavaScript(
//            'vd-climate-policy',
//            'EXT:vd_climate_policy/Resources/Public/JavaScript/vd-directory.min.js'
//        );
    }

    protected function getCssForColor(): string
    {
        $colors = GeneralUtility::makeInstance(RecordRepository::class)->fetchSectorColors();

        $css = '.vd-climate-policy .card:after {'
            . LF . '  content: "";'
            . LF . '  height: 2em;'
            . LF . '}'
            . LF . LF;

        foreach ($colors as $color) {
            $uid = strtolower((string)$color['uid']);

            if ($uid === '') {
                continue;
            }

            if ($color === '') {
                $color = '#82afcc';
            }

            $color = strpos($color['color'], '#') === 0 ? $color['color'] : '#' . $color['color'];
            $color = str_replace('#', '%23', $color);

            $css .= '.vd-climate-policy .card-' . $uid . ':after {'
                . LF . '  background-image: url("data:image/svg+xml,%3Csvg height=\'4\' width=\'4\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 0h1v1H0zM2 2h1v1H2z\' fill=\'' . $color . '\'/%3E%3C/svg%3E");'
                . LF . '}'
                . LF . LF;
        }

        return $css;
    }

    protected function writeStyleSheetContentToTemporaryFile(string $content): string
    {
        $script = 'typo3temp/assets/css/' . md5($content) . '.css';
        $scriptPath = Environment::getPublicPath() . '/' . $script;

        if (is_file($scriptPath) === false) {
            GeneralUtility::writeFileToTypo3tempDir($scriptPath, $content);
        }

        return $script;
    }
}
