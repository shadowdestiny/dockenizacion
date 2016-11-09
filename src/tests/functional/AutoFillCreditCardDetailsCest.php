<?php

class AutoFillCreditCardDetailsCest
{
    private $userId;
    private $userName;
    private $userSurname;

    public function _before(FunctionalTester $I)
    {
        $user = $I->setRegisteredUser();
        $this->userId = $user->getId();
        $this->userName = $user->getName();
        $this->userSurname = $user->getSurname();
    }

    public function _after(FunctionalTester $I)
    {

    }

    /**
     * @param FunctionalTester $I
     * @group active
     */
    public function seeNameInCardHolderAtBalancePage(FunctionalTester $I)
    {
        $I->haveInSession('EM_current_user', $this->userId);
        $I->amOnPage('/account/wallet');
        $I->canSee('Enter your credit card details');
        $I->assertNotEmpty($I->grabValueFrom('#card-holder'));
    }
}
