<?php


namespace tests\unit;

use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\PurchaseTransaction;
use EuroMillions\web\entities\DepositTransaction;

class TransactionEntityUnitTest extends UnitTestBase
{
    public function getTransactionTypeAndExpected()
    {
        $user = UserMother::aUserWith500Eur()->build();
        $now = new \DateTime();
        $wallet_before = $user->getWallet();
        $wallet_after = $user->getWallet();

        $data1 = [
            'lottery_id' => 1,
            'numBets' => 3,
            'amountWithWallet' => 2000,
            'amountWithCreditCard' => 0,
            'feeApplied' => 0,
            'user' => $user,
            'walletBefore' => $wallet_before,
            'walletAfter' => $wallet_after,
            'now' => $now,
            'playConfigs' => [1,2],
            'amount' => 0,
            'transactionID' => '123',
            'lotteryName' => 'TEST'
        ];

        $data2 = [
            'lottery_id' => 2,
            'numBets' => 4,
            'amountWithWallet' => 3000,
            'amountWithCreditCard' => 0,
            'feeApplied' => 0,
            'user' => $user,
            'walletBefore' => $wallet_before,
            'walletAfter' => $wallet_after,
            'now' => $now,
            'playConfigs' => [1,2],
            'amount' => 0,
            'transactionID' => '123',
            'lotteryName' => 'TEST'
        ];


        return [
            [$data1],
            [$data2]
        ];
    }

    /**
     * method isPendingTransaction
     * when called
     * should return true
     * @dataProvider getTransactionTypeAndExpected
     */

    public function test_isPendingTransaction_called_isAPendingTransaccion_hasGetStatusMethod($data)
    {
        $sut=new DepositTransaction($data + ['status' => 'PENDING']);
        $this->assertTrue($sut->isPendingTransaction());
    }

    /**
     * method isPendingTransaction
     * when called
     * should return false
     * @dataProvider getTransactionTypeAndExpected
     */

    public function test_isPendingTransaction_called_isNotAPendingTransaccion_hasGetStatusMethod($data)
    {
        $sut=new DepositTransaction($data + ['status' => 'SUCCESS']);
        $this->assertNotTrue($sut->isPendingTransaction());
    }

    /**
     * method isPendingTransaction
     * when called
     * should return false
     */

    public function test_isPendingTransaction_called_isNotAPendingTransaccion_hasNotGetStatusMethod()
    {
        $sut=new PurchaseTransaction([]);
        $this->assertNotTrue($sut->isPendingTransaction());
    }

    /**
     * method isErrorTransaction
     * when called
     * should return true
     * @dataProvider getTransactionTypeAndExpected
     */

    public function test_isErrorTransaction_called_isAErrorTransaccion_hasGetStatusMethod($data)
    {
        $sut=new DepositTransaction($data + ['status' => 'ERROR']);
        $this->assertTrue($sut->isErrorTransaction());
    }

    /**
     * method isErrorTransaction
     * when called
     * should return false
     * @dataProvider getTransactionTypeAndExpected
     */

    public function test_isErrorTransaction_called_isNotAErrorTransaccion_hasGetStatusMethod($data)
    {
        $sut=new DepositTransaction($data + ['status' => 'SUCCESS']);
        $this->assertNotTrue($sut->isErrorTransaction());
    }

    /**
     * method isErrorTransaction
     * when called
     * should return false
     */

    public function test_isErrorTransaction_called_isNotAErrorTransaccion_hasNotGetStatusMethod()
    {
        $sut=new PurchaseTransaction([]);
        $this->assertNotTrue($sut->isErrorTransaction());
    }
}