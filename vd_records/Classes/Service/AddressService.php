<?php

declare(strict_types=1);

namespace Vd\VdRecords\Service;

use function array_filter;
use function array_map;
use function implode;
use function preg_split;
use function sprintf;
use function strtoupper;
use function trim;

class AddressService
{
    public function getOneLineAddress(array $fields): string
    {
        return implode(
            ', ',
            array_filter([
                $this->getAddress($fields['address'] ?? ''),
                $this->getLocality($fields['zip'] ?? '', $fields['city'] ?? ''),
                $this->getCountry($fields['country'] ?? '')
            ])
        );
    }

    protected function getAddress(string $address): string
    {
        return trim(
            implode(
                ' ',
                array_filter(
                    array_map(
                        static fn (string $line): string => trim($line),
                        preg_split('/\R/u', $address) ?: []
                    )
                )
            )
        );
    }

    protected function getCountry(string $country): string
    {
        return strtoupper(trim($country));
    }

    protected function getLocality(string $zip, string $city): string
    {
        return trim(sprintf('%s %s', $zip, $city));
    }
}
