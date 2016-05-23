<?php
use EuroMillions\tests\helpers\builders\UserBuilder;
/**
 * Class AccountCest
 */
class AccountCest
{
    private $userId;
    private $userName;

    public function _before(FunctionalTester $I)
    {
        $user = $I->setRegisteredUser();
        $this->userName = $user->getName();
        $this->userId = $user->getId();
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
    public function seeAccountPageAfterLogin(FunctionalTester $I, $scenario, \Page\Login $loginPage)
    {
        $loginPage->login(UserBuilder::DEFAULT_EMAIL, UserBuilder::DEFAULT_PASSWORD);
        $I->amOnPage('/account');
        $I->canSee('Hello. '.$this->userName);
    }

    public function seeAccountPageAfterSignUp(FunctionalTester $I, $scenario, \Page\SignUp $signUpPage, \Page\Login $loginPage)
    {
        $scenario->skip();
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

    /**
     * @param FunctionalTester $I
     * @group active
     */
    public function seeAccountMyGames( FunctionalTester $I )
    {

        $I->haveInDatabase('play_configs', [
            'id'                        => 1,
            'user_id'                   => '9098299B-14AC-4124-8DB0-19571EDABE55',
            'active'                    => 1,
            'start_draw_date'           => '2016-04-08',
            'last_draw_date'            => '2016-04-08',
            'line_regular_number_one'   => '2',
            'line_regular_number_two'   => '3',
            'line_regular_number_three' => '4',
            'line_regular_number_four'  => '5',
            'line_regular_number_five'  => '6',
            'line_lucky_number_one'     => '1',
            'line_lucky_number_two'     => '2',
        ]);

        $I->haveInSession('EM_current_user', $this->userId);
        $I->amOnPage('/account/games');
        $I->canSee('My Tickets');
        $I->seeNumberOfElements('table.present tr', 1);
    }

    /**
     * @param FunctionalTester $I
     */
    public function seeTransactionWinningWhenWinningLessThan2500( FunctionalTester $I )
    {
        $I->haveInDatabase('transactions', [
            'id'    => 1,
            'user_id'   => '9098299B-14AC-4124-8DB0-19571EDABE55',
            'date'  => '2016-05-10 13:59:06',
            'wallet_before_uploaded_amount'    => '0',
            'wallet_before_uploaded_currency_name' => 'EUR',
            'wallet_before_winnings_amount'   => '3000',
            'wallet_before_winnings_currency_name'  => 'EUR',
            'wallet_after_uploaded_amount' => '0',
            'wallet_after_uploaded_currency_name'  => 'EUR',
            'wallet_after_winnings_amount'  => '4000',
            'wallet_after_winnings_currency_name'   => 'EUR',
            'entity_type'     => 'winnings_received',
            'data' => '1#1#1000#'
        ]);
        $I->haveInSession('EM_current_user', $this->userId);
        $I->amOnPage('/account/transaction');
        $I->canSee('Transaction');
        $I->seeNumberOfElements('table tbody tr', 1);
    }

    /**
     * @param FunctionalTester $I
     * @group active
     */
    public function dontSeeTransactionWinningWhenWinningIsGreaterThan2500( FunctionalTester $I )
    {
        $I->haveInDatabase('transactions', [
            'id'    => 1,
            'user_id'   => '9098299B-14AC-4124-8DB0-19571EDABE55',
            'date'  => '2016-05-10 13:59:06',
            'wallet_before_uploaded_amount'    => '0',
            'wallet_before_uploaded_currency_name' => 'EUR',
            'wallet_before_winnings_amount'   => '15909900',
            'wallet_before_winnings_currency_name'  => 'EUR',
            'wallet_after_uploaded_amount' => '0',
            'wallet_after_uploaded_currency_name'  => 'EUR',
            'wallet_after_winnings_amount'  => '15909650',
            'wallet_after_winnings_currency_name'   => 'EUR',
            'entity_type'     => 'big_winning',
            'data' => '1#1#15909650#pending'
        ]);

        $I->haveInSession('EM_current_user', $this->userId);
        $I->amOnPage('/account/transaction');
        $I->canSee('Transaction');
        $I->seeNumberOfElements('table tbody tr', 0);
    }

//    /**
//     * @group active
//     * @param FunctionalTester $I
//     */
//    public function seeCorrectlyMessageWhenIDoWithdraw( FunctionalTester $I )
//    {
//        //$user = UserMother::aUserWith40EurWinnings()->build();
//        $I->haveInSession('EM_current_user', $this->userId);
//        $I->amOnPage('/account/wallet');
//        $I->click('.withdraw');
//        $I->canSee('Withdraw your winnings');
//
//    }
}
