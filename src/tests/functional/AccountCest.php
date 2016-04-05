<?php
use EuroMillions\tests\helpers\builders\UserBuilder;
use EuroMillions\tests\helpers\mothers\UserMother;
/**
 * Class AccountCest
 */
class AccountCest
{
    private $userId;
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
        $I->canSee('User detail');
    }

    /**
     * @param FunctionalTester $I
     * @param \Page\Login $loginPage
     */
    public function seeAccountPageAfterLogin(FunctionalTester $I, \Page\Login $loginPage)
    {
        $loginPage->login(UserBuilder::DEFAULT_EMAIL, UserBuilder::DEFAULT_PASSWORD);
        $I->amOnPage('/account');
        $I->canSee('Hello. '.$this->userName);
    }

    /**
     * @param FunctionalTester $I
     * @param \Page\SignUp $signUpPage
     * @param \Page\Login $loginPage
     * @group active
     */
    public function seeAccountPageAfterSignUp(FunctionalTester $I, \Page\SignUp $signUpPage, \Page\Login $loginPage)
    {
        $email = 'nuevoemail@email.com';
        $password = 'Nuevopassword01';
        $nombre = 'Nuevo nombre';
        $signUpPage->signUp(
            $nombre,
            'Nuevo apellido',
            $email,
            $password,
            $password,
            UserBuilder::DEFAULT_COUNTRY
        );
        $I->canSeeNumRecords(1, 'users', ['email' => $email]);
        $loginPage->login($email, $password);
        $I->amOnPage('/account');
        $I->canSee('Hello. '.$nombre);
    }
}
