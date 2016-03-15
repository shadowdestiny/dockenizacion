<?php
namespace EuroMillions\tests\unit;

use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\services\SiteConfigService;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\entities\SiteConfig;
use Money\Currency;
use Money\Money;

class SiteConfigServiceToTest extends SiteConfigService
{
    public function getConfigEntity()
    {
        return $this->configEntity;
    }
}

class SiteConfigServiceUnitTest extends UnitTestBase
{
    protected $siteConfigRepository_double;

    public function setUp()
    {
        $this->siteConfigRepository_double = $this->getRepositoryDouble('SiteConfigRepository');
        parent::setUp();
    }

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'SiteConfig' => $this->siteConfigRepository_double
        ];
    }

    /**
     * method __construct
     * when calledWithoutSiteConfigInDatabase
     * should returnDefaultValues
     */
    public function test___construct_calledWithoutSiteConfigInDatabase_returnDefaultValues()
    {
        $expected = new SiteConfig();
        $expected->initialize([
            'id' => '1',
            'fee' => new Money(35, new Currency('EUR')),
            'fee_to_limit' => new Money(1200, new Currency('EUR')),
            'default_currency' => new Currency('EUR'),
        ]);
        $this->siteConfigRepository_double->getSiteConfig()->willReturn([]);
        $sut = new SiteConfigServiceToTest($this->getEntityManagerRevealed(), $this->getServiceDouble('CurrencyConversionService')->reveal());
        self::assertEquals($expected, $sut->getConfigEntity());
    }

}
