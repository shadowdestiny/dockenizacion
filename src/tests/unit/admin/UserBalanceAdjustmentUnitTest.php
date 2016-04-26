<?php


namespace EuroMillions\tests\unit\admin;


use EuroMillions\admin\vo\UserBalanceAdjustment;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\tests\base\UnitTestBase;
use Money\Currency;
use Money\Money;

class UserBalanceAdjustmentUnitTest extends UnitTestBase
{


    /**
     * method __construct
     * when called
     * should createAProperObject
     */
    public function test___construct_called_createAProperObject()
    {
        $wallet = Wallet::create(2000,3000);
        $data = [
            'amount' => 1000,
            'withdrawable' => 0,
            'reason' => 'Error with update balance'
        ];
        $amount = new Money( (int) $data['amount'], new Currency('EUR'));
        $sut = new UserBalanceAdjustment($wallet, $data);
        $this->assertEquals($amount,$sut->getAmount());
        $this->assertEquals('Error with update balance', $sut->getReason());
    }

    /**
     * method deductUserBalance
     * when called
     * should deductAmountFromUserBalance
     */
    public function test_deductUserBalance_called_deductAmountFromUserBalance()
    {
        $expected = new Money(4000, new Currency('EUR'));
        $actual = $this->prepareDataToAdjustmentBalance(5000,'','deductUserBalance');
        $this->assertEquals($expected->getAmount(),$actual->getBalance()->getAmount());
    }

    /**
     * method increaseUserBalance
     * when called
     * should increaseAmountFromUserBalance
     */
    public function test_increaseUserBalance_called_increaseAmountFromUserBalance()
    {
        $expected = new Money(6000, new Currency('EUR'));
        $actual = $this->prepareDataToAdjustmentBalance(5000,'','increaseUserBalance');
        $this->assertEquals($expected->getAmount(), $actual->getBalance()->getAmount());
    }

    /**
     * method createAdjustmentBalance
     * when calledPassingAnAmountNegative
     * should deductUserBalance
     */
    public function test_createAdjustmentBalance_calledPassingAnAmountNegative_deductUserBalance()
    {
        $expected = new Money(4000, new Currency('EUR'));
        $actual = $this->prepareDataToAdjustmentBalance(5000,'-','createAdjustmentBalance');
        $this->assertEquals($expected->getAmount(),$actual->getBalance()->getAmount());
    }

    /**
     * method createdAdjustmentBalance
     * when calledPassingAnAmountPositive
     * should increaseUserBalance
     */
    public function test_createdAdjustmentBalance_calledPassingAnAmountPositive_increaseUserBalance()
    {
        $expected = new Money(6000, new Currency('EUR'));
        $actual = $this->prepareDataToAdjustmentBalance(5000,'','createAdjustmentBalance');
        $this->assertEquals($expected->getAmount(),$actual->getBalance()->getAmount());

    }

    private function prepareDataToAdjustmentBalance($amountBalance, $operand = '', $method)
    {
        $wallet = Wallet::create($amountBalance,0);
        $data = [
            'amount' => $operand.'1000',
            'withdrawable' => 0,
            'reason' => 'Error with update balance'
        ];
        $sut = new UserBalanceAdjustment($wallet, $data);
        $sut->$method();
        $actual = $sut->getWallet();
        return $actual;
    }

}