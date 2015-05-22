<?php
namespace tests\integration;

use tests\base\RepositoryIntegrationTestBase;

class TranslationDetailRepositoryIntegrationTest extends RepositoryIntegrationTestBase
{
    /** @var \EuroMillions\repositories\TranslationDetailRepository */
    protected $sut;

    protected function getFixtures()
    {
        return [
            'languages',
            'translations',
            'translation_details'
        ];
    }

    public function setUp()
    {
        parent::setUp('TranslationDetail');
    }

    public function test_getTranslation_calledWithNonExistingKey_returnKey()
    {
        $key = 'non_existing_key';
        $actual = $this->sut->getTranslation('en', $key);
        $this->assertEquals($key, $actual);
    }
}