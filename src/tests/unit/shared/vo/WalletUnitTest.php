<?php
namespace tests\unit\shared\vo;

use EuroMillions\apps\shared\vo\Wallet;
use Money\Currency;
use Money\Money;
use tests\base\UnitTestBase;

class WalletUnitTest extends UnitTestBase
{
    /**
     * method upload
     * when called
     * should addAmountToUploaded
     * @dataProvider getUploadTestCasesData
     */
    public function test_upload_called_addAmountToUploaded($uploaded, $winnings, $amount)
    {
        $uploadedInWallet = $this->getMoney($uploaded);
        $winningsInWallet = $this->getMoney($winnings);
        $sut = new Wallet($uploadedInWallet, $winningsInWallet);
        $amountToUpload = $this->getMoney($amount);
        $sut->upload($amountToUpload);
        $this->assertEquals($uploadedInWallet->add($amountToUpload), $sut->getUploaded());
        $this->assertEquals($winningsInWallet, $sut->getWinnings());
    }

    public function getUploadTestCasesData()
    {
        return [
            [0, 0, 10],
            [10, 0, 10],
            [10, 10, 10],
            [10, 10, 0]
        ];
    }

    /**
     * method award
     * when called
     * should addAmountToWinnings
     */
    public function test_award_called_addAmountToWinnings()
    {

    }

    /**
     * method payPreservingWinnings
     * when calledWithEnoughFunds
     * should substractFundsFromUploadedButNotFromWinnings
     */
    public function test_payPreservingWinnings_calledWithEnoughFunds_substractFundsFromUploadedButNotFromWinnings()
    {

    }

    /**
     * method payPreservingWinnings
     * when calledWithouthEnoughFunds
     * should throw
     */
    public function test_payPreservingWinnings_calledWithouthEnoughFunds_throw()
    {

    }

    /**
     * method payUsingWinnigs
     * when calledWithEnoughFunds
     * should substractFundsFromUploadedFirstAndThenFromWinnings
     */
    public function test_payUsingWinnigs_calledWithEnoughFunds_substractFundsFromUploadedFirstAndThenFromWinnings()
    {

    }

    /**
     * method payUsingWinnings
     * when calledWithouthEnoughFunds
     * should throw
     */
    public function test_payUsingWinnings_calledWithouthEnoughFunds_throw()
    {

    }

    /**
     * method widthdraw
     * when called
     * should substractAmountFromWinnings
     */
    public function test_widthdraw_called_substractAmountFromWinnings()
    {

    }

    /**
     * method withdraw
     * when calledWithouthEnoughFunds
     * should throw
     */
    public function test_withdraw_calledWithouthEnoughFunds_throw()
    {

    }

    private function getMoney($amount)
    {
        return new Money($amount, new Currency('EUR'));
    }
}