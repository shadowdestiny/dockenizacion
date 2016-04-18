<?php
namespace EuroMillions\tests\unit\shared\vo;

use Phalcon\Loader;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\shared\vo\Wallet;
use Money\Currency;
use Money\Money;

class WalletUnitTest extends UnitTestBase
{
    const NOT_ENOUGH_FUNDS_EXCEPTION = 'EuroMillions\shared\exceptions\NotEnoughFunds';

    /**
     * method upload
     * when called
     * should returnNewObjectWithNewUploadedAmount
     * @dataProvider getUploadAndAwardTestCasesData
     */
    public function test_upload_called_returnNewObjectWithNewUploadedAmount($uploaded, $winnings, $amountToUpload)
    {
        list($uploadedInWallet, $winningsInWallet, $amountUploadedToWallet) = $this->getMoneyObjects($uploaded, $winnings, $amountToUpload);
        $sut = new Wallet($uploadedInWallet, $winningsInWallet);
        $actual = $sut->upload($amountUploadedToWallet);
        $expected_withdrawable = $winnings;
        $expected_balance = $uploaded + $winnings + $amountToUpload;
        $expected_sut_withdrawable = $winnings;
        $expected_sut_balance = $uploaded + $winnings;
        $this->assertWalletValuesFromActualAndSut($actual, $sut, $expected_balance, $expected_withdrawable, $expected_sut_balance, $expected_sut_withdrawable);
    }

    /**
     * method award
     * when called
     * should returnNewObjectWithNewAwardAmount
     * @dataProvider getUploadAndAwardTestCasesData
     */
    public function test_award_called_returnNewObjectWithNewAwardAmount($uploaded, $winnings, $amountToAward)
    {
        list($uploadedInWallet, $winningsInWallet, $amountAwardedToWallet) = $this->getMoneyObjects($uploaded, $winnings, $amountToAward);
        $sut = new Wallet($uploadedInWallet, $winningsInWallet);
        $actual = $sut->award($amountAwardedToWallet);
        $expected_withdrawable = $winnings+$amountToAward;
        $expected_balance = $uploaded + $winnings + $amountToAward;
        $expected_sut_withdrawable = $winnings;
        $expected_sut_balance = $uploaded + $winnings;
        $this->assertWalletValuesFromActualAndSut(
            $actual, $sut, $expected_balance, $expected_withdrawable, $expected_sut_balance, $expected_sut_withdrawable
        );
    }


    public function getUploadAndAwardTestCasesData()
    {
        return [
            [0, 0, 0],
            [0, 0, 10],
            [0, 10, 10],
            [10, 0, 10],
            [10, 10, 0],
            [10, 10, 10],
        ];
    }

    /**
     * method pay
     * when called
     * should substractFirstFromNonWidthdrawableAndThenFromWithdrable
     * @dataProvider getInitialBalancesAndPayments
     */
    public function test_pay_called_substractFirstFromNonWidthdrawableAndThenFromWithdrable($uploaded, $winnings, $amountToPay, $balanceLeft, $widtdrawableLeft)
    {
        list ($uploadedInWallet, $winningsInWallet, $amountPayed) = $this->getMoneyObjects($uploaded, $winnings, $amountToPay);
        $sut = new Wallet($uploadedInWallet, $winningsInWallet);
        $actual = $sut->pay($amountPayed);
        $this->assertWalletValuesFromActualAndSut($actual, $sut, $balanceLeft, $widtdrawableLeft, $uploaded+$winnings, $winnings);
    }

    public function getInitialBalancesAndPayments()
    {
        // uploaded, winnings, amount to pay, balance, withdrawable
        return [
            [50, 0, 10, 40, 0],
            [40, 100, 20, 120, 100],
            [20, 100, 30, 90, 90],
            [0, 140, 50, 90, 90],
        ];
    }

    /**
     * method pay
     * when calledWithouthEnoughFunds
     * should throw
     */
    public function test_pay_calledWithouthEnoughFunds_throw()
    {
        $uploaded = 200;
        $winnings = 400;
        $this->setExpectedException(self::NOT_ENOUGH_FUNDS_EXCEPTION);
        $this->exercisePay($uploaded, $winnings, 700);
    }

    /**
     * method getBalance
     * when called
     * should returnSumOfUploadedAndWinnings
     * @dataProvider getAmountsAndBalance
     */
    public function test_getBalance_called_returnSumOfUploadedAndWinnings($uploaded, $winnings, $expectedBalance)
    {
        $sut = new Wallet($this->getMoney($uploaded), $this->getMoney($winnings));
        $this->assertEquals($this->getMoney($expectedBalance), $sut->getBalance());
    }

    public function getAmountsAndBalance()
    {
        return [
            [0,0,0],
            [100,0,100],
            [0,100,100],
            [51,52,103],
            [52,51,103],
        ];
    }

    /**
     * method create
     * when called
     * should createProperWalletObject
     */
    public function test_create_called_createProperWalletObject()
    {
        $actual = Wallet::create(3000, 400);
        $expected = new Wallet($this->getMoney(3000), $this->getMoney(400));
        $this->assertEquals($expected, $actual);
    }

    /**
     * method equal
     * when calledOnObjectsWithSameValues
     * should returnTrue
     */
    public function test_equal_called_returnTrue()
    {
        $wallet = new Wallet($this->getMoney(40), $this->getMoney(30));
        $wallet2 = (new Wallet($this->getMoney(30), $this->getMoney(10)))
            ->upload($this->getMoney(20))
            ->award($this->getMoney(20))
            ->pay($this->getMoney(10));
        $this->assertTrue($wallet->equals($wallet2));
    }

    private function getMoney($amount)
    {
        return new Money($amount, new Currency('EUR'));
    }

    /**
     * @param $uploaded
     * @param $winnings
     * @param $amount
     * @return array
     */
    private function getMoneyObjects($uploaded, $winnings, $amount)
    {
        $uploadedInWallet = $this->getMoney($uploaded);
        $winningsInWallet = $this->getMoney($winnings);
        $amountToUpload = $this->getMoney($amount);
        return array($uploadedInWallet, $winningsInWallet, $amountToUpload);
    }

    /**
     * @param $uploaded
     * @param $winnings
     * @param $payment
     * @return Wallet
     * @internal param $method
     */
    private function exercisePay($uploaded, $winnings, $payment)
    {
        $sut = new Wallet($this->getMoney($uploaded), $this->getMoney($winnings));
        return $sut->pay($this->getMoney($payment));
    }

    /**
     * @param $actual
     * @param $sut
     * @param $expected_balance
     * @param $expected_withdrawable
     * @param $expected_sut_balance
     * @param $expected_sut_withdrawable
     */
    private function assertWalletValuesFromActualAndSut($actual, $sut, $expected_balance, $expected_withdrawable, $expected_sut_balance, $expected_sut_withdrawable)
    {
        self::assertEquals($expected_balance, $actual->getBalance()->getAmount());
        self::assertEquals($expected_withdrawable, $actual->getWithdrawable()->getAmount());
        self::assertEquals($expected_sut_balance, $sut->getBalance()->getAmount());
        self::assertEquals($expected_sut_withdrawable, $sut->getWithdrawable()->getAmount());
        self::assertNotSame($actual, $sut);
    }

}