<?php
use EuroMillions\tests\helpers\builders\UserBuilder;
use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;
use EuroMillions\tests\helpers\mothers\EuroMillionsLineMother;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\Bet;

/**
 * Class AccountCest
 */
class AccountCest
{
    private $userId;
    private $userName;
    private $user;
    private $draw;

    public function _before(FunctionalTester $I)
    {
//        $this->lottery = \EuroMillions\tests\helpers\mothers\LotteryMother::anEuroMillions();
//
//        $I->haveInDatabase(
//            'lotteries',
//            $this->lottery->toArray()
//        );
//
//        $this->lottery = \EuroMillions\tests\helpers\mothers\LotteryMother::aMegaMillions();
//
//        $I->haveInDatabase(
//            'lotteries',
//            $this->lottery->toArray()
//        );
//
//        $this->lottery = \EuroMillions\tests\helpers\mothers\LotteryMother::aPowerBall();
//
//        $I->haveInDatabase(
//            'lotteries',
//            $this->lottery->toArray()
//        );

//        $I->haveInDatabase(
//            'languages',
//            ['id'   => 1,
//             'ccode' => 'en',
//             'active' => true,
//             'defaultLocale' => 'en_US']
//        );
//
//        $I->haveInDatabase(
//            'currencies',
//            ['code' => 'EUR', 'name' => 'Euro', 'order' => '1']
//        );

        $this->draw = \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
            ->withId(1)
            ->withDrawDate(new \DateTime('2020-01-10'))
            ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
            ->build();

        $I->haveInDatabase(
            'euromillions_draws',
            $this->draw->toArray()
        );

        $user = $I->setRegisteredUser();
        $this->user = $user;
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
        //$I->seeInShellOutput('i dont know');
        $I->canSee('My Tickets');
        //$I->seeNumberOfElements('table.present', 1);

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
        $I->amOnPage('/profile/transactions');
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

    /**
     * @param FunctionalTester $I
     * @group account
     */
    public function seePaginationInTicketsSection(FunctionalTester $I)
    {
//        $this->preparePastTicketsToPaginate($I);
//        //        $user = $I->setRegisteredUser();
////        $this->user = $user;
////        $this->userName = $user->getName();
////        $this->userId = $user->getId();
//        // $I->setCookie('EM_current_user', $this->userId);
//        $I->haveInSession('EM_current_user', $this->userId);
//        $I->amOnPage('/profile/tickets/games');
    }



    private function preparePastTicketsToPaginate(FunctionalTester $I)
    {
        $powerBallLottery = \EuroMillions\tests\helpers\mothers\LotteryMother::aPowerBall();

        $draw = \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown(new \DateTime('2018-01-01'))->build();
        $playConfig = PlayConfigMother::aPlayConfigSetForUser($this->user)
            ->withLine(EuroMillionsLineMother::anOtherPowerBallLine())
            ->withLottery($powerBallLottery)
            ->withNoActive()
            ->withStartDrawDate(new DateTime('2018-02-01'))
            ->withLastDrawDate(new DateTime('2018-02-01'))
            ->build();
        $bet = new Bet($playConfig, $draw);
        $bet->setPrize(new \Money\Money(10000, new \Money\Currency('EUR')));
        $I->haveInDatabase('euromillions_draws', $draw->toArray());
        $I->haveInDatabase('play_configs', $playConfig->toArray());
        $I->haveInDatabase('bets', $bet->toArray());

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