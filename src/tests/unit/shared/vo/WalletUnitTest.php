<?php
namespace tests\unit\shared\vo;

use Phalcon\Loader;
use tests\base\UnitTestBase;
use EuroMillions\shared\vo\Wallet;
use Money\Currency;
use Money\Money;

class WalletUnitTest extends UnitTestBase
{
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
        $expected_uploaded = $this->getMoney($expected);
        $expected_winnings = $this->getMoney($winnings);
        $this->assertEquals($expected_uploaded, $sut->getUploaded());
        $this->assertEquals($expected_winnings, $sut->getWinnings());
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
        $this->setExpectedException('EuroMillions\shared\exceptions\NotEnoughFunds');
        $sut = $this->exercisePayPreservingWinnings($uploaded, $winnings, 500);
        $this->assertEquals($this->getMoney($uploaded), $sut->getUploaded());
        $this->assertEquals($this->getMoney($winnings), $sut->getWinnings());

    }

    /**
     * method payUsingWinnigs
     * when calledWithEnoughFunds
     * should substractFundsFromUploadedFirstAndThenFromWinnings
     */
    public function test_payUsingWinnigs_calledWithEnoughFunds_substractFundsFromUploadedFirstAndThenFromWinnings()
    {
        $this->markTestIncomplete();

    }

    /**
     * method payUsingWinnings
     * when calledWithouthEnoughFunds
     * should throw
     */
    public function test_payUsingWinnings_calledWithouthEnoughFunds_throw()
    {
        $this->markTestIncomplete();

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
        $sut = new Wallet($this->getMoney($uploaded), $this->getMoney($winnings));
        $sut->payPreservingWinnings($this->getMoney($payment));
        return $sut;
    }
}