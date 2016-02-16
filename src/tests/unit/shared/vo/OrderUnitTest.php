<?php


namespace tests\unit\shared\vo;


use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\vo\DrawDays;
use EuroMillions\web\vo\Order;
use Money\Currency;
use Money\Money;
use tests\base\UnitTestBase;
use tests\helpers\mothers\OrderMother;
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
     * method addFunds
     * when calledWithAmountGreaterThanFeeLimit
     * should chargeFeeMethodReturnFalse
     */
    public function test_addFunds_calledWithAmountGreaterThanFeeLimit_chargeFeeMethodReturnFalse()
    {
        $sut = $this->getSut();
        $sut->addFunds(new Money(12000,new Currency('EUR')));
        $actual = $sut->is_charged_fee();
        $this->assertEquals(false,$actual);
    }

    /**
     * method is_charged_fee
     * when called
     * should returnTrue
     */
    public function test_is_charged_fee_called_returnTrue()
    {
        $sut = $this->getSut();
        $actual = $sut->is_charged_fee();
        $this->assertEquals(true,$actual);
    }


    /**
     * method hasNextDraw
     * when called
     * should returnTrue
     */
    public function test_hasNextDraw_called_returnTrue()
    {
        $order = OrderMother::aJustOrder()->build();
        $play_config = $order->getPlayConfig();
        $play_config[0]->setStartDrawDate(new \DateTime('2016-02-09 10:00:00'));
        $play_config[0]->setDrawDays(new DrawDays(25));
        $play_config[0]->setLastDrawDate(new \DateTime('2016-02-09 22:00:00'));
        $draw_date = new \DateTime('2016-02-09 20:00:00');
        $actual = $order->isNextDraw($draw_date);
        $this->assertEquals(true,$actual);
    }


    /**
     * method hasNextDraw
     * when calledWithnvalidDate
     * should returnFalse
     */
    public function test_hasNextDraw_calledWithnvalidDate_returnFalse()
    {
        $order = OrderMother::aJustOrder()->build();
        $play_config = $order->getPlayConfig();
        $play_config[0]->setStartDrawDate(new \DateTime('2016-02-10 10:00:00'));
        $play_config[0]->setDrawDays(new DrawDays(5));
        $play_config[0]->setLastDrawDate(new \DateTime('2016-02-09 22:00:00'));
        $draw_date = new \DateTime('2016-02-09 20:00:00');
        $actual = $order->isNextDraw($draw_date);
        $this->assertEquals(false,$actual);
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
        $sut = new Order($bets, $single_bet_price, $fee, $fee_limit);
        return $sut;
    }


}