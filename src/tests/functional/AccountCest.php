<?php
use EuroMillions\tests\helpers\mothers\UserMother;
/**
 * Class AccountCest
 */
class AccountCest
{
    private $userId;
    private $userEmail;
    private $userName;
    private $password;
    const USER_NAME = 'Antonio';

    public function _before(FunctionalTester $I)
    {
        $user = UserMother::anAlreadyRegisteredUser()->withName(self::USER_NAME)->build();
        $this->userEmail = $user->getEmail();
        $this->userName = $user->getName();
        $this->password = $user->getPassword();
        $this->userId = $user->getId();
        $I->haveInDatabase('users', $user->toArray());
   }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * @param FunctionalTester $I
     * @group active
     */
    public function redirectToLoginIfNotLoggedIn(FunctionalTester $I)
    {
        $I->amOnPage('/account');
        $I->seeCurrentUrlMatches('/sign-in/');
    }

//    public function seeAccountPageWhenImLoggedIn(FunctionalTester $I)
//    {
//        $I->haveInSession('EM_current_user', $this->userId);
//        $I->amOnPage('/account');
//        $I->canSee('Hello. '.$this->userName);
//    }
//
//    /**
//     * @param FunctionalTester $I
//     * @param \Page\Login $loginPage
//     * @group active
//     */
//    public function seeAccountPageAfterLogin(FunctionalTester $I, \Page\Login $loginPage)
//    {
//        $loginPage->login($this->userEmail, $this->password);
//        $I->amOnPage('/account');
//        $I->canSee('Hello. '.$this->username);
//    }
}
