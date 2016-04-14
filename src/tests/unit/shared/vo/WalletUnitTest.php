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
     * should addAmountToUploaded
     * @dataProvider getUploadAndAwardTestCasesData
     */
    public function test_upload_called_addAmountToUploaded($uploaded, $winnings, $amount)
    {
        list($uploadedInWallet, $winningsInWallet, $amountToUpload) = $this->getMoneyObjects($uploaded, $winnings, $amount);
        $expected_uploaded = $uploadedInWallet->add($amountToUpload);
        $expected_winnings = $winningsInWallet;
        $method = 'upload';
        $this->exercise($method, $uploadedInWallet, $winningsInWallet, $amountToUpload, $expected_uploaded, $expected_winnings);
    }

    /**
     * method upload
     * when walledIsNotInitialized
     * should setAmountAsUpload
     */
    public function test_upload_walledIsNotInitialized_setAmountAsUpload()
    {
        $sut = new Wallet();
        $amount = $this->getMoney(10);
        $sut->upload($amount);
        $this->assertEquals($amount, $sut->getUploaded());
    }

    /**
     * method upload
     * when called
     * should returnANewInstanceFromWallet
     */
    public function test_upload_called_returnAnewInstanceFromWallet()
    {
        $sut = Wallet::create(100000,0);
        $actual = $sut->upload(new Money(2000,new Currency('EUR')));
        $this->assertFalse($actual === $sut);
    }

    /**
     * method award
     * when called
     * should returnANewInstanceFromWallet
     */
    public function test_award_called_returnANewInstanceFromWallet()
    {
        $sut = Wallet::create(100000,0);
        $actual = $sut->award(new Money(2000,new Currency('EUR')));
        $this->assertFalse($actual === $sut);
    }

    /**
     * method award
     * when called
     * should addAmountToWinnings
     * @dataProvider getUploadAndAwardTestCasesData
     */
    public function test_award_called_addAmountToWinnings($uploaded, $winnings, $amount)
    {
        list($uploadedInWallet, $winningsInWallet, $amountToAward) = $this->getMoneyObjects($uploaded, $winnings, $amount);
        $expected_uploaded = $uploadedInWallet;
        $expected_winnings = $winningsInWallet->add($amountToAward);
        $method = 'award';
        $this->exercise($method, $uploadedInWallet, $winningsInWallet, $amountToAward, $expected_uploaded, $expected_winnings);
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
     * method payPreservingWinnings
     * when calledWithEnoughFunds
     * should substractFundsFromUploadedButNotFromWinnings
     * @dataProvider getAmountsForPayPreservingWinnings
     * @param $uploaded
     * @param $winnings
     * @param $payment
     * @param $expected
     */
    public function test_payPreservingWinnings_calledWithEnoughFunds_substractFundsFromUploadedButNotFromWinnings($uploaded, $winnings, $payment, $expected)
    {
        $sut = $this->exercisePayPreservingWinnings($uploaded, $winnings, $payment);
        $this->assertEquals($this->getMoney($expected), $sut->getUploaded());
        $this->assertEquals($this->getMoney($winnings), $sut->getWinnings());
    }

    public function getAmountsForPayPreservingWinnings()
    {
        return [
            [4000, 2500, 3500, 500],
            [4000, 2500, 4000, 0],
        ];
    }

    /**
     * method payPreservingWinnings
     * when calledWithouthEnoughFunds
     * should throw
     */
    public function test_payPreservingWinnings_calledWithouthEnoughFunds_throw()
    {
        $uploaded = 200;
        $winnings = 400;
        $this->setExpectedException(self::NOT_ENOUGH_FUNDS_EXCEPTION);
        $sut = $this->exercisePayPreservingWinnings($uploaded, $winnings, 500);
        $this->assertEquals($this->getMoney($uploaded), $sut->getUploaded());
        $this->assertEquals($this->getMoney($winnings), $sut->getWinnings());
    }

    /**
     * method payUsingWinnigs
     * when calledWithEnoughFunds
     * should substractFundsFromUploadedFirstAndThenFromWinnings
     * @dataProvider getAmountsForPayUsingWinnings
     */
    public function test_payUsingWinnigs_calledWithEnoughFunds_substractFundsFromUploadedFirstAndThenFromWinnings($uploaded, $winnings, $payment, $expectedUploaded, $expectedWinnings)
    {
        $sut = $this->exercisePayUsingWinnings($uploaded, $winnings, $payment);
        $this->assertEquals($this->getMoney($expectedUploaded), $sut->getUploaded());
        $this->assertEquals($this->getMoney($expectedWinnings), $sut->getWinnings());
    }

    public function getAmountsForPayUsingWinnings()
    {
        return [
            [4000, 2500, 3500, 500, 2500],
            [4000, 2500, 4000, 0, 2500],
            [4000, 2500, 4500, 0, 2000],
            [4000, 2500, 6500, 0, 0],
            [0, 2500, 2300, 0, 200],
        ];
    }

    /**
     * method payUsingWinnings
     * when calledWithouthEnoughFunds
     * should throw
     */
    public function test_payUsingWinnings_calledWithouthEnoughFunds_throw()
    {
        $uploaded = 400;
        $winnings = 600;
        $payment = 1001;
        $this->setExpectedException(self::NOT_ENOUGH_FUNDS_EXCEPTION);
        $sut = $this->exercisePayUsingWinnings($uploaded, $winnings, $payment);
        $this->assertEquals($this->getMoney($uploaded), $sut->getUploaded());
        $this->assertEquals($this->getMoney($winnings), $sut->getWinnings());
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
     * method widthdraw
     * when called
     * should substractAmountFromWinnings
     */
    public function test_widthdraw_called_substractAmountFromWinnings()
    {
        $this->markTestIncomplete();

    }

    /**
     * method withdraw
     * when calledWithouthEnoughFunds
     * should throw
     */
    public function test_withdraw_calledWithouthEnoughFunds_throw()
    {
        $this->markTestIncomplete();

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
     * when called
     * should returnTrue
     */
    public function test_equal_called_returnTrue()
    {
        $wallet = new Wallet();
        $actual = new Wallet();
        $sut = $actual->equals($wallet);
        $this->assertTrue($sut);
    }

    private function getMoney($amount)
    {
        return new Money($amount, new Currency('EUR'));
    }

    /**
     * @param $method
     * @param $uploadedInWallet
     * @param $winningsInWallet
     * @param $amountToUpload
     * @param $expectedUploaded
     * @param $expectedWinnings
     */
    private function exercise($method, $uploadedInWallet, $winningsInWallet, $amountToUpload, $expectedUploaded, $expectedWinnings)
    {
        $sut = new Wallet($uploadedInWallet, $winningsInWallet);
        $sut->$method($amountToUpload);
        $this->assertEquals($expectedUploaded, $sut->getUploaded());
        $this->assertEquals($expectedWinnings, $sut->getWinnings());
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
     */
    private function exercisePayPreservingWinnings($uploaded, $winnings, $payment)
    {
        return $this->exercisePay($uploaded, $winnings, $payment, 'payPreservingWinnings');
    }

    private function exercisePayUsingWinnings($uploaded, $winnings, $payment)
    {
        return $this->exercisePay($uploaded, $winnings, $payment, 'payUsingWinnings');
    }

    /**
     * @param $uploaded
     * @param $winnings
     * @param $payment
     * @param $method
     * @return Wallet
     */
    private function exercisePay($uploaded, $winnings, $payment, $method)
    {
        $sut = new Wallet($this->getMoney($uploaded), $this->getMoney($winnings));
        $sut->$method($this->getMoney($payment));
        return $sut;
    }

}