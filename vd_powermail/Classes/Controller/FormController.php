<?php

declare(strict_types=1);

namespace Vd\VdPowermail\Controller;

use In2code\Powermail\Controller\FormController as DefaultFormController;
use In2code\Powermail\Domain\Model\Answer;
use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Repository\AnswerRepository;
use In2code\Powermail\Utility\StringUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;

class FormController extends DefaultFormController
{
    protected function reformatParamsForAction(): void
    {
        $this->uploadService->preflight($this->settings);
        $arguments = $this->request->getArguments();
        if (!isset($arguments['field'])) {
            return;
        }
        $newArguments = [
            'mail' => $arguments['mail']
        ];

        // allow subvalues in new property mapper
        $mailMvcArgument = $this->arguments->getArgument('mail');
        $propertyMapping = $mailMvcArgument->getPropertyMappingConfiguration();
        $propertyMapping->allowProperties('answers');
        $propertyMapping->allowCreationForSubProperty('answers');
        $propertyMapping->allowModificationForSubProperty('answers');
        $propertyMapping->allowProperties('form');
        $propertyMapping->allowCreationForSubProperty('form');
        $propertyMapping->allowModificationForSubProperty('form');

        // allow creation of new objects (for validation)
        $propertyMapping->setTypeConverterOptions(
            PersistentObjectConverter::class,
            [
                PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED => true,
                PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED => true
            ]
        );

        $formUid = (int)$arguments['mail']['form'];
        $iteration = 0;

        foreach ((array)$arguments['field'] as $marker => $value) {
            // ignore internal fields (honeypod)
            if (StringUtility::startsWith((string)$marker, '__')) {
                continue;
            }
            $fieldUid = $this->fieldRepository->getFieldUidFromMarker($marker, $formUid);
            // Skip fields without Uid (secondary password, upload)
            if ($fieldUid === 0) {
                continue;
            }

            // allow subvalues in new property mapper
            $propertyMapping->forProperty('answers')->allowProperties($iteration);
            $propertyMapping->forProperty('answers.' . $iteration)->allowAllProperties();
            $propertyMapping->allowCreationForSubProperty('answers.' . $iteration);
            $propertyMapping->allowModificationForSubProperty('answers.' . $iteration);

            /** @var Field $field */
            $field = $this->fieldRepository->findByUid($fieldUid);
            $valueType = $field->dataTypeFromFieldType(
                $this->fieldRepository->getFieldTypeFromMarker($marker, $formUid)
            );
            if ($valueType === Answer::VALUE_TYPE_UPLOAD && is_array($value)) {
                $value = $this->uploadService->getNewFileNamesByMarker($marker);
            }
            if (is_array($value)) {
                if (empty($value)) {
                    $value = '';
                } else {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                }
            }
            $newArguments['mail']['answers'][$iteration] = [
                'field' => (string)$fieldUid,
                'value' => $value,
                'valueType' => $valueType
            ];

            // edit form: add answer id
            if (!empty($arguments['field']['__identity'])) {
                $answerRepository = GeneralUtility::makeInstance(AnswerRepository::class);
                $answer = $answerRepository->findByFieldAndMail($fieldUid, $arguments['field']['__identity']);

                if ($answer !== null) {
                    $newArguments['mail']['answers'][$iteration]['__identity'] = $answer->getUid();
                }
            }

            ++$iteration;
        }

        // edit form: add mail id
        if (!empty($arguments['field']['__identity'])) {
            $newArguments['mail']['__identity'] = $arguments['field']['__identity'];
        }

        $this->request->setArguments($newArguments);
        $this->request->setArgument('field', null);
    }
}
