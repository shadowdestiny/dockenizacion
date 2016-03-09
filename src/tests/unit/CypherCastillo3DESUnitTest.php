<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\LotteryValidationCastilloRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

class CypherCastillo3DESUnitTest extends UnitTestBase
{

    use LotteryValidationCastilloRelatedTest;

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * method encrypt
     * when called
     * should returnCypheredContentXML
     */
    public function test_encrypt_called_returnCypheredContentXML()
    {
        $xml_content = $this->getXmlContent();
        $sut = $this->getSut();
        $actual = $sut->encrypt(self::$key,$xml_content);
        $this->assertEquals(self::$cyphered_content,$actual);
    }

    /**
     * method decrypt
     * when called
     * should returnDecryptXMLContent
     */
    public function test_decrypt_called_returnDecryptXMLContent()
    {
        $expected = $this->getXmlContent();
        $sut = $this->getSut();
        $actual = $sut->decrypt(self::$cyphered_content,self::$key);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getSignature
     * when called
     * should returnValidSignatureWithSHA1Sign
     */
    public function test_getSignature_called_returnValidSignatureWithSHA1Sign()
    {
        $expected = self::$cyphered_signature;
        $content_cyphered = self::$cyphered_content;
        $sut= $this->getSut();
        $actual = $sut->getSignature($content_cyphered);
        $this->assertEquals($expected,$actual);
    }

    public function getSut()
    {
        return new \EuroMillions\web\components\CypherCastillo3DES();
    }


}