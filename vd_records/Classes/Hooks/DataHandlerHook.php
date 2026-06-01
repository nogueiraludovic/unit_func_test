<?php

declare(strict_types=1);

namespace Vd\VdRecords\Hooks;

use Vd\VdRecords\Service\AddressService;

class DataHandlerHook
{
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function processDatamap_preProcessFieldArray(array &$fields, string $table): void
    {
        if ($table !== 'tt_address') {
            return;
        }

        $fullAddress = $this->addressService->getOneLineAddress($fields);

        if ($fullAddress === '') {
            return;
        }

        $fields['full_address'] = $fullAddress;
    }
}
