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


    public function seeAccountMyGames( FunctionalTester $I )
    {

        $I->haveInDatabase('play_configs', [
            'id'                        => 1,
            'user_id'                   => '9098299B-14AC-4124-8DB0-19571EDABE55',
            'active'                    => 1,
            'start_draw_date'           => '2016-04-08',
            'last_draw_date'            => '2016-04-08',
            'threshold_amount'          => 0,
            'threshold_currency_name'   => '',
            'line_regular_number_one'   => '2',
            'line_regular_number_two'   => '3',
            'line_regular_number_three' => '4',
            'line_regular_number_four'  => '5',
            'line_regular_number_five'  => '6',
            'line_lucky_number_one'     => '1',
            'line_lucky_number_two'     => '2',
            'config'                    => 5
        ]);

        $I->haveInSession('EM_current_user', $this->userId);
        $I->amOnPage('/account/games');
        $I->canSee('Present Games');
        $I->seeNumberOfElements('table.present tr', 1);
    }
}
