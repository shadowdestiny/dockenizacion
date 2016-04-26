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
     * method __construct
     * when calledPassingAnAmountNegative
     * should deductUserBalance
     */
    public function test___construct_calledPassingAnAmountNegative_deductUserBalance()
    {
        $expected = new Money(4000, new Currency('EUR'));
        $actual = $this->prepareDataToAdjustmentBalance(5000,'-');
        $this->assertEquals($expected->getAmount(),$actual->getBalance()->getAmount());
    }

    /**
     * method __construct
     * when calledPassingAnAmountPositive
     * should increaseUserBalance
     */
    public function test___construct_calledPassingAnAmountPositive_increaseUserBalance()
    {
        $expected = new Money(6000, new Currency('EUR'));
        $actual = $this->prepareDataToAdjustmentBalance(5000,'');
        $this->assertEquals($expected->getAmount(),$actual->getBalance()->getAmount());
    }

    /**
     * method __construct
     * when calledWithWithdrawableTrueAndAmountPositive
     * should increaseWinningUserBalance
     */
    public function test___construct_calledWithWithdrawableTrueAndAmountPositive_increaseWinningUserBalance()
    {
        $expected = new Money(4000, new Currency('EUR'));
        $actual = $this->prepareDataToAdjustmentBalance(5000,'',1,3000);
        $this->assertEquals($expected,$actual->getWithdrawable());
    }

    /**
     * method __construct
     * when calledWithWithdrawableTrueAndAmountNegative
     * should decreaseWinningsUserBalance
     */
    public function test___construct_calledWithWithdrawableTrueAndAmountNegative_decreaseWinningsUserBalance()
    {
        $expected = new Money(500, new Currency('EUR'));
        $actual = $this->prepareDataToAdjustmentBalance(5000,'-',1,3000,'2500');
        $this->assertEquals($expected,$actual->getWithdrawable());
    }

    private function prepareDataToAdjustmentBalance($amountBalance, $operand = '', $withdrawable = 0, $winningAmount = 0,$amount = '1000')
    {
        $wallet = Wallet::create($amountBalance, $winningAmount);
        $data = [
            'amount' => $operand.$amount,
            'withdrawable' => $withdrawable,
            'reason' => 'Error with update balance'
        ];
        $sut = new UserBalanceAdjustment($wallet, $data);
        $actual = $sut->getWallet();
        return $actual;
    }

}