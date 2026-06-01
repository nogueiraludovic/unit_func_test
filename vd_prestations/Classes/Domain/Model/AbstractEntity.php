<?php

declare(strict_types=1);

namespace Vd\VdPrestations\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity as ExtbaseAbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

use function method_exists;
use function time;
use function ucfirst;

abstract class AbstractEntity extends ExtbaseAbstractEntity
{
    protected bool $hidden = false;
    protected int $tstamp = 0;
    protected array $updateFields = [];

    public function getTstamp(): int
    {
        return $this->tstamp;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): AbstractEntity
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function setTstamp(int $tstamp): AbstractEntity
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    public function toArray(): array
    {
        $properties = $this->_getProperties();

        $unsetProperties = [
            'updateFields'
        ];

        foreach ($unsetProperties as $unsetProperty) {
            unset($properties[$unsetProperty]);
        }

        foreach ($properties as $fieldName => $value) {
            $getter = 'get' . ucfirst($fieldName);

            if (($value instanceof ObjectStorage) === true && method_exists($this, $getter) === true) {
                $properties[$fieldName] = [];

                foreach ($value as $record) {
                    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
                    $properties[$fieldName][] = $record->toArray();
                }
            }
        }

        return $properties;
    }

    public function updateFrom($object): void
    {
        foreach ($this->updateFields as $updateField) {
            $setter = 'set' . ucfirst($updateField);
            $getter = 'get' . ucfirst($updateField);

            if (method_exists($this, $setter) === true && method_exists($object, $getter) === true) {
                $this->$setter($object->$getter());
            }
        }

        if (method_exists($this, 'setTstamp') === true) {
            $this->setTstamp(time());
        }
    }
}
