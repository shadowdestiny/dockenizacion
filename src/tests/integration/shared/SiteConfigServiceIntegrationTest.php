<?php
namespace tests\integration\shared;

use EuroMillions\shared\services\SiteConfigService;
use tests\base\DatabaseIntegrationTestBase;

class SiteConfigServiceSpy extends SiteConfigService
{
    public function getConfigEntity()
    {
        return $this->configEntity;
    }
}

class SiteConfigServiceIntegrationTest extends DatabaseIntegrationTestBase
{

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return ['site_config'];
    }

    /**
     * method __construct
     * when called
     * should createProperEntity
     */
    public function test___construct_called_createProperEntity()
    {
        $sut = new SiteConfigServiceSpy($this->entityManager);

        $expected = [
            'config1' => 'el valor de la config1',
            'config2' => 'el valor de la config2',
            'config3' => 'el valor de la config3',
        ];

        $this->assertEquals($expected, $sut->getConfigEntity());
    }

    /**
     * method get
     * when calledWithAValidConfigName
     * should returnConfigValue
     */
    public function test_get_calledWithAValidConfigName_returnConfigValue()
    {
        $sut = new SiteConfigService($this->entityManager);
        $actual = $sut->get('config1');
        $expected = 'el valor de la config1';
        $this->assertEquals($expected, $actual);
    }
}