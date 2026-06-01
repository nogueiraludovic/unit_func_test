<?php

namespace Vd\VdPrestations\Tests\Unit\Utility;

use DOMDocument;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use Vd\VdPrestations\Utility\XmlValidatorUtility;

use function file_get_contents;

final class XmlValidatorUtilityTest extends UnitTestCase
{
    private const XML_PRESTATION_ADD_EXAMPLE = 'Resources/Private/Tests/prestation_validation_add.xml';
    private const XML_PRESTATION_DELETE_EXAMPLE = 'Resources/Private/Tests/prestation_validation_delete.xml';
    private const XML_PRESTATION_EDIT_EXAMPLE = 'Resources/Private/Tests/prestation_validation_edit.xml';

    /**
     * @test
     * @group vd
     * @group vd_prestations
     * @throws \Vd\VdPrestations\Exception\InvalidXmlException
     */
    public function validateXmlAddExampleTest(): void
    {
        $this->expectNotToPerformAssertions();
        $this->resetSingletonInstances = true;
        $xmlPath = ExtensionManagementUtility::extPath('vd_prestations') . self::XML_PRESTATION_ADD_EXAMPLE;
        $xmlDocument = new DOMDocument();
        $xmlDocument->loadXML(file_get_contents($xmlPath));
        XmlValidatorUtility::validateXml($xmlDocument);
    }

    /**
     * @test
     * @group vd
     * @group vd_prestations
     * @throws \Vd\VdPrestations\Exception\InvalidXmlException
     */
    public function validateXmlDeleteExampleTest(): void
    {
        $this->expectNotToPerformAssertions();
        $this->resetSingletonInstances = true;
        $xmlPath = ExtensionManagementUtility::extPath('vd_prestations') . self::XML_PRESTATION_DELETE_EXAMPLE;
        $xmlDocument = new DOMDocument();
        $xmlDocument->loadXML(file_get_contents($xmlPath));
        XmlValidatorUtility::validateXml($xmlDocument);
    }

    /**
     * @test
     * @group vd_unit
     * @group vd_unit_prestations
     * @throws \Vd\VdPrestations\Exception\InvalidXmlException
     */
    public function validateXmlEditExampleTest(): void
    {
        $this->expectNotToPerformAssertions();
        $this->resetSingletonInstances = true;
        $xmlPath = ExtensionManagementUtility::extPath('vd_prestations') . self::XML_PRESTATION_EDIT_EXAMPLE;
        $xmlDocument = new DOMDocument();
        $xmlDocument->loadXML(file_get_contents($xmlPath));
        XmlValidatorUtility::validateXml($xmlDocument);
    }
}
