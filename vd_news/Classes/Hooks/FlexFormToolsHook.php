<?php

declare(strict_types=1);

namespace Vd\VdNews\Hooks;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function file_get_contents;

class FlexFormToolsHook
{
    public function parseDataStructureByIdentifierPostProcess(array $dataStructure, array $identifier): array
    {
        if (
            $identifier['dataStructureKey'] !== 'news_pi1,list'
            || $identifier['tableName'] !== 'tt_content'
            || $identifier['type'] !== 'tca'
        ) {
            return $dataStructure;
        }

        $content = file_get_contents(
            Environment::getPublicPath() . '/typo3conf/ext/vd_news/Configuration/FlexForms/RSS.xml'
        );

        if ($content === false) {
            return $dataStructure;
        }

        $dataStructure['sheets']['rss'] = GeneralUtility::xml2array($content);

        return $dataStructure;
    }
}
