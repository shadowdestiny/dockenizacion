<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\controllers\PublicSiteControllerBase;
use EuroMillions\web\vo\EuroMillionsJackpot;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\ControllerUnitTestBase;


class PublicSiteControllerBaseToTest extends PublicSiteControllerBase
{

    public function setTopNavValuesToTest()
    {
        $this->setTopNavValues();
    }

}


class PublicSiteControllerBaseUnitTest extends ControllerUnitTestBase
{
    protected $lotteriyService_double;
    protected $languageService_double;
    protected $currencyService_double;
    protected $userService_double;
    protected $authService_double;
    protected $userPreferencesService_double;
    protected $siteConfigService_double;
    protected $cartService_double;
    protected $currencyConversionService_double;


    public function setUp()
    {
        $this->lotteriyService_double = $this->getServiceDouble('LotteryService');
        $this->languageService_double = $this->getServiceDouble('LanguageService');
        $this->currencyService_double = $this->getServiceDouble('CurrencyService');
        $this->userService_double = $this->getServiceDouble('UserService');
        $this->authService_double = $this->getServiceDouble('AuthService');
        $this->userPreferencesService_double = $this->getServiceDouble('UserPreferencesService');
        $this->siteConfigService_double = $this->getSharedServiceDouble('SiteConfigService');
        $this->cartService_double = $this->getServiceDouble('CartService');
        $this->currencyConversionService_double = $this->getServiceDouble('CurrencyConversionService');
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
        $jackpot = EuroMillionsJackpot::fromAmountIncludingDecimals(15000000);
        $bet_price = new Money(250, $current_currency);
        $pound_currency = new Currency('GBP');
        $bet_price_pound = new Money(150, $pound_currency);
        $bet_price_to_string = 'valor de la apuesta';
        $bet_price_pound_to_string = 'valor de la apuesta en pounds';

        $this->userPreferencesService_double->getMyCurrencyNameAndSymbol()->willReturn($user_currency);
        $this->userPreferencesService_double->getCurrency()->willReturn($current_currency);
        $this->authService_double->isLogged()->willReturn(false);
        $this->lotteriyService_double->getNextJackpot('EuroMillions')->willReturn($jackpot);
        $this->userPreferencesService_double->getJackpotInMyCurrency($jackpot)->willReturn((int) $jackpot->getAmount());
        $this->lotteriyService_double->getNextDateDrawByLottery('EuroMillions')->willReturn(new \DateTime()); //I don't care about this, I won't check the countdown
        $this->lotteriyService_double->getSingleBetPriceByLottery('EuroMillions')->willReturn($bet_price);
        $this->currencyConversionService_double->convert($bet_price, $current_currency)->willReturn($bet_price);
        $this->currencyConversionService_double->toString($bet_price, $current_currency)->willReturn($bet_price_to_string);
        $this->currencyConversionService_double->convert($bet_price, $pound_currency)->willReturn($bet_price_pound);
        $this->currencyConversionService_double->toString($bet_price_pound, $pound_currency)->willReturn($bet_price_pound_to_string);

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
            $this->lotteriyService_double->reveal(),
            $this->languageService_double->reveal(),
            $this->currencyService_double->reveal(),
            $this->userService_double->reveal(),
            $this->authService_double->reveal(),
            $this->userPreferencesService_double->reveal(),
            $this->siteConfigService_double->reveal(),
            $this->cartService_double->reveal(),
            $this->currencyConversionService_double->reveal()
        );
        $sut->setTopNavValuesToTest();
    }


}