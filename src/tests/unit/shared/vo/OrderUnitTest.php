<?php


namespace tests\unit\shared\vo;


use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\vo\Order;
use Money\Currency;
use Money\Money;
use tests\base\UnitTestBase;
use tests\helpers\mothers\UserMother;

class OrderUnitTest extends UnitTestBase
{

    /**
     * method getNumLines
     * when called
     * should returnNumLinesPlayedInOrder
     */
    public function test_getNumLines_called_returnNumLinesPlayedInOrder()
    {
        $sut = $this->getSut();
        $actual = $sut->getNumLines();
        $this->assertEquals(2,$actual);
    }


    /**
     * method getTotal
     * when called
     * should returnMoneyWithTotalDependsFromPlayConfig
     */
    public function test_getTotal_called_returnMoneyWithTotalDependsFromPlayConfig()
    {
        $sut = $this->getSut();
        $expected = new Money(5035, new Currency('EUR'));
        $actual = $sut->getTotal();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getTotal
     * when totalLessThanFeeLimit
     * should addFeeToTotal
     */
    public function test_getTotal_totalLessThanFeeLimit_addFeeToTotal()
    {
        $sut = $this->getSut();
        $expected = new Money(5035, new Currency('EUR'));
        $actual = $sut->getTotal();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method addFunds
     * when called
     * should returnTotalWithFundsAdded
     */
    public function test_addFunds_called_returnTotalWithFundsAdded()
    {
        $expected = new Money(5135, new Currency('EUR'));
        $sut = $this->getSut();
        $sut->addFunds(new Money(100,new Currency('EUR')));
        $actual = $sut->getTotal();
        $this->assertEquals($expected,$actual);
    }

    /**
     * @return Order
     */
    private function getSut()
    {
        $string_json = '{"drawDays":"1","startDrawDate":"05 Feb 2016","lastDrawDate":"2016-02-05 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[3,8,11,16,44],"lucky":[3,5]},{"regular":[6,17,37,38,48],"lucky":[1,5]}]},"numbers":null,"threshold":null,"num_weeks":0}';
        $form_decode = json_decode($string_json);
        $bets = [];
        foreach($form_decode->euroMillionsLines->bets as $bet) {
            $bets[] = $bet;
        }
        $user = UserMother::aUserWith500Eur()->build();
        $playConfig = new PlayConfig();
        $playConfig->formToEntity($user,$string_json,$bets);

        $single_bet_price = new Money(2500, new Currency('EUR'));
        $fee = new Money(35, new Currency('EUR'));
        $fee_limit = new Money(12000, new Currency('EUR'));
        $sut = new Order($playConfig, $single_bet_price, $fee, $fee_limit);

        return $sut;
    }


}