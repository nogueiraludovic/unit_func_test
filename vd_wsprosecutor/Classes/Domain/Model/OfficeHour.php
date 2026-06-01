<?php

declare(strict_types=1);

namespace Vd\VdWsprosecutor\Domain\Model;

use DateTimeImmutable;
use Locale;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function json_encode;
use function setlocale;
use function strftime;
use function strpos;

use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const LC_TIME;

class OfficeHour
{
    protected array $record;

    public function __construct(array $record)
    {
        $endShift = $this->getDate($record['endDate']);
        $endShiftTimestamp = $endShift->getTimestamp();

        $startShift = $this->getDate($record['startDate']);
        $startShiftTimestamp = $startShift->getTimestamp();

        $this->record = [
            'api_response' => json_encode($record, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR),
            'end_shift' => $endShiftTimestamp,
            'end_shift_date' => $endShift->format('d.m.Y'),
            'end_shift_date_hour' => $endShift->format('d.m.Y H:i'),
            'end_shift_day' => $this->getDay($endShiftTimestamp),
            'end_shift_hour' => $this->getHour($endShiftTimestamp),
            'event' => isset($record['evenement']) === true ? $record['evenement'] : '',
            'external_id' => $record['id'] ?? '',
            'magistrate_first_name' => isset($record['magistrat']['prenom']) === true
                ? $record['magistrat']['prenom']
                : '',
            'magistrate_id' => isset($record['magistrat']['id']) === true ? $record['magistrat']['id'] : '',
            'magistrate_last_name' => isset($record['magistrat']['nom']) === true ? $record['magistrat']['nom'] : '',
            'magistrate_name' => isset($record['magistrat']['nomPrenom']) === true
                ? $record['magistrat']['nomPrenom']
                : '',
            'modification_date' => $this->getDate($record['modificationDate'])->getTimestamp(),
            'office' => $this->getOffice(
                $record['section']['libelleOfficePermanence'] ?? null,
                $record['evenement'] ?? null
            ),
            'pid' => 0,
            'start_shift' => $startShiftTimestamp,
            'start_shift_date' => $startShift->format('d.m.Y'),
            'start_shift_date_hour' => $startShift->format('d.m.Y H:i'),
            'start_shift_day' => $this->getDay($startShiftTimestamp),
            'start_shift_hour' => $this->getHour($startShiftTimestamp),
            'substitute_first_name' => isset($record['suppleant']['prenom']) === true
                ? $record['suppleant']['prenom']
                : '',
            'substitute_id' => isset($record['suppleant']['id']) === true ? $record['suppleant']['id'] : '',
            'substitute_last_name' => isset($record['suppleant']['nom']) === true ? $record['suppleant']['nom'] : '',
            'substitute_name' => isset($record['suppleant']['nomPrenom']) === true
                ? $record['suppleant']['nomPrenom']
                : ''
        ];
    }

    public function getRecord(): array
    {
        return $this->record;
    }

    protected function getDate(string $date): DateTimeImmutable
    {
        $dateObject = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.u', $date)
            ?: DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', $date);

        return $dateObject ?: new DateTimeImmutable();
    }

    protected function getDay(int $timestamp): string
    {
        $defaultLocale = Locale::getDefault();

        setlocale(LC_TIME, 'fr_CH.UTF-8');

        $day = strftime('%A', $timestamp);

        setlocale(LC_TIME, $defaultLocale);

        return $day;
    }

    protected function getHour(int $timestamp): string
    {
        $hourParts = GeneralUtility::trimExplode(
            ':',
            (new DateTimeImmutable())->setTimestamp($timestamp)->format('H:i'),
            true,
            2
        );

        if ($hourParts[1] === '00') {
            return $hourParts[0] . 'h';
        }

        return $hourParts[0] . 'h' . $hourParts[1];
    }

    protected function getOffice(?string $office, ?string $event): string
    {
        if (isset($event) === true && $event !== '' && isset($office) === true && strpos($office, 'MP') === 0) {
            return 'MP ' . $event;
        }

        return isset($office) === true ? $office : '';
    }
}
