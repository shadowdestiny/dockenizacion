<?php


namespace EuroMillions\tests\unit\shared\vo;


use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\vo\Order;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;

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
        $this->assertEquals(4,$actual);
    }


    /**
     * method getTotal
     * when called
     * should returnMoneyWithTotalCharged
     */
    public function test_getTotal_called_returnMoneyWithTotalCharged()
    {
        $sut = $this->getSut();
        $expected = new Money(1000, new Currency('EUR'));
        $actual = $sut->getTotalFromUser();
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
        $expected = new Money(1035, new Currency('EUR'));
        $actual = $sut->getTotal();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getTotal
     * when payIsMix
     * should restWalletToTotalAndReturnNewCreditCardCharge
     */
    public function test_getTotal_payIsMix_restWalletToTotalAndReturnNewCreditCardCharge()
    {
        $sut = $this->getSut();
        $sut->setIsCheckedWalletBalance(true);
        $sut->setAmountWallet(new Money(1500,new Currency('EUR')));
        $expected = new Money(35, new Currency('EUR'));
        $actual = $sut->getTotal();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getTotal
     * when playWithWalletAndAddFunds
     * should returnCreditChargeWithProperTotal
     */
    public function test_getTotal_playWithWalletAndAddFunds_returnCreditChargeWithProperTotal()
    {
        $sut = $this->getSut();
        $sut->setIsCheckedWalletBalance(true);
        $sut->addFunds(new Money(500, new Currency('EUR')));
        $sut->setAmountWallet(new Money(1500,new Currency('EUR')));
        $expected = new Money(535, new Currency('EUR'));
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
        $expected = new Money(1135, new Currency('EUR'));
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
        $string_json = '{"play_config":[{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[16,18,20,21,32],"lucky":[4,8]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[3,22,23,30,44],"lucky":[7,9]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[31,37,39,44,47],"lucky":[4,10]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[25,31,33,38,47],"lucky":[2,6]}]},"numbers":null,"threshold":null,"num_weeks":0}]}';
        $user = UserMother::aUserWith500Eur()->build();
        $form_decode = json_decode($string_json);
        $bets = [];
        foreach($form_decode->play_config as $bet) {
            $playConfig = new PlayConfig();
            $playConfig->formToEntity($user,$bet,$bet->euroMillionsLines);
            $bets[] = $playConfig;
        }

        $single_bet_price = new Money(250, new Currency('EUR'));
        $fee = new Money(35, new Currency('EUR'));
        $fee_limit = new Money(12000, new Currency('EUR'));
        return new Order($bets, $single_bet_price, $fee, $fee_limit);
    }


}