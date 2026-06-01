<?php

declare(strict_types=1);

namespace Vd\VdNews\TCA\Form;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdNews\Database\RecordRepository;

use function strrpos;
use function substr;

class FilterProvider
{
    public function forNews(array $parameters): array
    {
        $recordRepository = GeneralUtility::makeInstance(RecordRepository::class);

        foreach ($parameters['values'] as $value) {
            if ($recordRepository->countNewsByTypeAndUid(3, (int)substr($value, strrpos($value, '_') + 1)) === 0) {
                continue;
            }

            $values[] = $value;
        }

        return $values ?? [];
    }
}
