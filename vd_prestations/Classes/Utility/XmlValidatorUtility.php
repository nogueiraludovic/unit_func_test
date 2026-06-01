<?php

namespace Vd\VdPrestations\Utility;

use DOMDocument;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Vd\VdPrestations\Exception\InvalidXmlException;

use function libxml_clear_errors;
use function libxml_get_errors;
use function libxml_use_internal_errors;

class XmlValidatorUtility
{
    protected const XSD_FILE = 'Resources/Private/Webservice/typo3-prestation-1-0.xsd';

    public static function validateXml(DOMDocument $xmlDocument): void
    {
        $logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);

        libxml_use_internal_errors(true);

        if ($xmlDocument->schemaValidate(ExtensionManagementUtility::extPath('vd_prestations') . self::XSD_FILE) === true) {
            return;
        }

        $errors = libxml_get_errors();

        foreach ($errors as $error) {
            // @extensionScannerIgnoreLine
            $logger->error(
                '[Prestation] Error when validating XSD, code 154360030',
                [
                    'error' => $error->message, 'xml' => $xmlDocument->saveXML()
                ]
            );
        }

        libxml_clear_errors();

        throw new InvalidXmlException('The provided XML does not match the XSD.');
    }
}
