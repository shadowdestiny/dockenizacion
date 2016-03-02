<?php


namespace tests\unit;


use EuroMillions\web\controllers\PublicSiteControllerBase;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\ControllerUnitTestBase;


class PublicSiteControllerBaseToTest extends PublicSiteControllerBase
{

    public function setTopNavValuesToTest()
    {
        $this->setTopNavValues();
    }

}


class PublicSiteControllerBaseUnitTest extends ControllerUnitTestBase
{


    protected $lotteriesDataService_double;

    protected $languageService_double;

    protected $currencyService_double;

    protected $userService_double;

    protected $authService_double;

    protected $userPreferencesService_double;

    protected $siteConfigService_double;

    protected $cartService_double;


    public function setUp()
    {
        $this->lotteriesDataService_double = $this->getServiceDouble('LotteriesDataService');
        $this->languageService_double = $this->getServiceDouble('LanguageService');
        $this->currencyService_double = $this->getServiceDouble('CurrencyService');
        $this->userService_double = $this->getServiceDouble('UserService');
        $this->authService_double = $this->getServiceDouble('AuthService');
        $this->userPreferencesService_double = $this->getServiceDouble('UserPreferencesService');
        $this->siteConfigService_double = $this->getSharedServiceDouble('SiteConfigService');
        $this->cartService_double = $this->getServiceDouble('CartService');
        parent::setUp();
    }

    /**
     * method setTopNavValues
     * when calledWithoutUserLogged
     * should setProperVars
     */
    public function test_setTopNavValues_calledWithoutUserLogged_setProperVars()
    {
        $user_currency = ['symbol' => '€', 'name' => 'Euro'];
        $current_currency_name = 'EUR';
        $current_currency = new Currency($current_currency_name);
        $jackpot = new Money(1500000000, $current_currency);
        $bet_price = new Money(250, $current_currency);
        $pound_currency = new Currency('GBP');
        $bet_price_pound = new Money(150, $pound_currency);
        $bet_price_to_string = 'valor de la apuesta';
        $bet_price_pound_to_string = 'valor de la apuesta en pounds';

        $this->userPreferencesService_double->getMyCurrencyNameAndSymbol()->willReturn($user_currency);
        $this->userPreferencesService_double->getCurrency()->willReturn($current_currency);
        $this->authService_double->isLogged()->willReturn(false);
        $this->lotteriesDataService_double->getNextJackpot('EuroMillions')->willReturn($jackpot);
        $this->userPreferencesService_double->getJackpotInMyCurrency($jackpot)->willReturn($jackpot);
        $this->lotteriesDataService_double->getNextDateDrawByLottery('EuroMillions')->willReturn(new \DateTime()); //I don't care about this, I won't check the countdown
        $this->lotteriesDataService_double->getSingleBetPriceByLottery('EuroMillions')->willReturn($bet_price);
        $this->currencyService_double->convert($bet_price, $current_currency)->willReturn($bet_price);
        $this->currencyService_double->toString($bet_price, $current_currency)->willReturn($bet_price_to_string);
        $this->currencyService_double->convert($bet_price, $pound_currency)->willReturn($bet_price_pound);
        $this->currencyService_double->toString($bet_price_pound, $pound_currency)->willReturn($bet_price_pound_to_string);

        $user_balance = $user_balance_raw = '';

        $this->assertSetVarCalledWithData([
            'current_currency'   => $current_currency_name,
            'user_currency'      => $user_currency,
            'user_currency_code' => $current_currency_name,
            'user_balance' => $user_balance,
            'user_balance_raw' => $user_balance_raw,
            'jackpot'       => 15000000,
            'countdown_next_draw' => Argument::any(),
            'bet_price' => $bet_price_to_string,
            'bet_price_pound' => $bet_price_pound_to_string,
        ]);

        $sut = new PublicSiteControllerBaseToTest();
        $sut->initialize(
            $this->lotteriesDataService_double->reveal(),
            $this->languageService_double->reveal(),
            $this->currencyService_double->reveal(),
            $this->userService_double->reveal(),
            $this->authService_double->reveal(),
            $this->userPreferencesService_double->reveal(),
            $this->siteConfigService_double->reveal(),
            $this->cartService_double->reveal()
        );
        $sut->setTopNavValuesToTest();
    }


}