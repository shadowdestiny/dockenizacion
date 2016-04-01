<?php
use EuroMillions\tests\helpers\builders\UserBuilder;
use EuroMillions\tests\helpers\mothers\UserMother;
/**
 * Class AccountCest
 */
class AccountCest
{
    private $userId;
    private $userEmail;
    private $userName;

    public function _before(FunctionalTester $I)
    {
        $user = UserMother::aRegisteredUserWithEncryptedPassword()->build();
        $this->userName = $user->getName();
        $this->userId = $user->getId();
        $I->haveInDatabase('users', $user->toArray());
   }

    public function _after(FunctionalTester $I)
    {
    }

    public function redirectToLoginIfNotLoggedIn(FunctionalTester $I)
    {
        $I->amOnPage('/account');
        $I->seeCurrentUrlMatches('/^\/sign-in/');
    }

    public function seeAccountPageWhenImLoggedIn(FunctionalTester $I)
    {
        $I->haveInSession('EM_current_user', $this->userId);
        $I->amOnPage('/account');
        $I->canSee('Hello. '.$this->userName);
    }

    /**
     * @param FunctionalTester $I
     * @param \Page\Login $loginPage
     * @group active
     */
    public function seeAccountPageAfterLogin(FunctionalTester $I, \Page\Login $loginPage)
    {
        $loginPage->login(UserBuilder::DEFAULT_EMAIL, UserBuilder::DEFAULT_PASSWORD);
        $I->amOnPage('/account');
        $I->canSee('Hello. '.$this->userName);
    }
}
