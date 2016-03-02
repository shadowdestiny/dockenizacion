<?php


namespace tests\unit;


use EuroMillions\web\controllers\AccountController;
use EuroMillions\web\forms\CreditCardForm;
use tests\base\UnitTestBase;

class AccountControllerUnitTest extends UnitTestBase
{

    /**
     * method addFundsAction
     * when formPostedWithValidCreditCard
     * should increaseUploadedFundsOnUser
     */
    public function test_addFundsAction_formPostedWithValidCreditCard_increaseUploadedFundsOnUser()
    {

    }


    /**
     * method addFundsAction
     * when formNotPosted
     * should showForm
     */
    public function test_addFundsAction_formNotPosted_showForm()
    {

        $this->markTestIncomplete('Create this test -> addFundsAction from AccountController');
        $this->checkViewVarsContain('credit_card_form', new CreditCardForm());
      //  $sut = new AccountController();
//        $actual = $sut->addFundsAction();

//        $this->checkViewVarsContain();
//        $this->checkViewVarsContain();
//        $this->checkViewVarsContain();
//        $this->checkViewVarsContain();
    }


}