<?php


namespace tests\integration;


use tests\base\TranslationMigrationIntegrationTestBase;

class TranslatedStringsIntegrationTest extends TranslationMigrationIntegrationTestBase
{
    /**
     * method up
     * when called
     * should storeProperValues
     */
    public function test_up_called_storeProperValues()
    {
        $this->checkTranslationTree('TranslatedStrings', '20150518084938_translated_strings');
    }

}