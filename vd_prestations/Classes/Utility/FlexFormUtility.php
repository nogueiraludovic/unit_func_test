<?php

namespace Vd\VdPrestations\Utility;

use function array_key_exists;
use function count;
use function explode;
use function in_array;
use function is_array;
use function next;
use function strpos;

class FlexFormUtility
{
    public function normalizeFlexForm($flexForm, $languagePointer = 'lDEF', $valuePointer = 'vDEF'): array
    {
        $settings = [];
        $flexForm = $flexForm['data'] ?? [];

        foreach ($flexForm as $languages) {
            if (!is_array($languages[$languagePointer])) {
                continue;
            }

            foreach ($languages[$languagePointer] as $valueKey => $valueDefinition) {
                if (strpos($valueKey, '.') === false) {
                    $settings[$valueKey] = $this->walkFlexFormNode($valueDefinition, $valuePointer);
                } else {
                    $valueKeyParts = explode('.', $valueKey);
                    $currentNode = &$settings;

                    foreach ($valueKeyParts as $valueKeyPart) {
                        $currentNode = &$currentNode[$valueKeyPart];
                    }

                    if (is_array($valueDefinition)) {
                        if (array_key_exists($valuePointer, $valueDefinition)) {
                            $currentNode = $valueDefinition[$valuePointer];
                        } else {
                            $currentNode = $this->walkFlexFormNode($valueDefinition, $valuePointer);
                        }
                    } else {
                        $currentNode = $valueDefinition;
                    }
                }
            }
        }

        return $settings;
    }

    protected function walkFlexFormNode($nodeArray, $valuePointer = 'vDEF'): array
    {
        if (is_array($nodeArray)) {
            $return = [];

            foreach ($nodeArray as $nodeKey => $nodeValue) {
                if ($nodeKey === $valuePointer) {
                    return $nodeValue;
                }

                if (in_array($nodeKey, ['el', '_arrayContainer'])) {
                    return $this->walkFlexFormNode($nodeValue, $valuePointer);
                }

                if ($nodeKey[0] === '_') {
                    continue;
                }

                if (strpos($nodeKey, '.')) {
                    $nodeKeyParts = explode('.', $nodeKey);
                    $currentNode = &$return;
                    $nodeKeyPartsCount = count($nodeKeyParts);

                    for ($i = 0; $i < $nodeKeyPartsCount - 1; $i++) {
                        $currentNode = &$currentNode[$nodeKeyParts[$i]];
                    }

                    $newNode = [next($nodeKeyParts) => $nodeValue];
                    $currentNode = $this->walkFlexFormNode($newNode, $valuePointer);
                } elseif (is_array($nodeValue)) {
                    if (array_key_exists($valuePointer, $nodeValue)) {
                        $return[$nodeKey] = $nodeValue[$valuePointer];
                    } else {
                        $return[$nodeKey] = $this->walkFlexFormNode($nodeValue, $valuePointer);
                    }
                } else {
                    $return[$nodeKey] = $nodeValue;
                }
            }

            return $return;
        }

        return $nodeArray;
    }
}
