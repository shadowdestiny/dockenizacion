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
        list($uploadedInWallet, $winningsInWallet, $amountToUpload) = $this->getMoneyObjects($uploaded, $winnings, $amount);
        $expected_uploaded = $uploadedInWallet;
        $expected_winnings = $winningsInWallet->add($amountToUpload);
        $method = 'award';
        $this->exercise($method, $uploadedInWallet, $winningsInWallet, $amountToUpload, $expected_uploaded, $expected_winnings);
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
     */
    public function test_payPreservingWinnings_calledWithEnoughFunds_substractFundsFromUploadedButNotFromWinnings()
    {
        $this->markTestIncomplete();
    }

    /**
     * method payPreservingWinnings
     * when calledWithouthEnoughFunds
     * should throw
     */
    public function test_payPreservingWinnings_calledWithouthEnoughFunds_throw()
    {
        $this->markTestIncomplete();

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
}