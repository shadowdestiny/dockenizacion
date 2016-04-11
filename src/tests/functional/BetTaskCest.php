<?php


use EuroMillions\tests\helpers\builders\UserBuilder;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\UserMother;

class BetTaskCest
{

    protected $userName;
    protected $userId;

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

    /**
     * @param FunctionalTester $I
     */
    public function betsAreCreatedTest(FunctionalTester $I, \Page\Login $loginPage)
    {

        $user = UserMother::aUserWith50Eur()->build();
        $play_config_to_bet = PlayConfigMother::aPlayConfig();
        $play_config_to_bet->withUser($user);

        $play_config_to_no_bet = PlayConfigMother::aPlayConfig()->withStartDrawDate(new \DateTime('2015-04-22'))->build();
        $play_config_to_no_bet->setLastDrawDate(new \DateTime('2015-04-25'));


        $I->haveInDatabase('euromillions_draws', [
            'id'         => 1,
            'lottery_id' => 1,
            'draw_date'  => '2020-04-07',
        ]);

        $I->haveInDatabase('users', [
            'id'                            => '9098299B-14AC-4124-8DB0-19571EDABE59',
            'name'                          => 'Test',
            'surname'                       => 'Test1',
            'country'                       => 1,
            'validated'                     => 1,
            'password'                      => '123456',
            'email'                         => 'test@yopmail.com',
            'wallet_uploaded_amount'        => 300005,
            'wallet_uploaded_currency_name' => 'EUR',
            'wallet_winnings_amount'        => 0,
            'wallet_winnings_currency_name' => 'EUR',
        ]);

        $I->haveInDatabase('play_configs', [
            'id'                        => 1,
            'user_id'                   => '9098299B-14AC-4124-8DB0-19571EDABE59',
            'active'                    => 1,
            'start_draw_date'           => '2020-04-01',
            'last_draw_date'            => '2020-04-30',
            'threshold_amount'          => 0,
            'threshold_currency_name'   => '',
            'line_regular_number_one'   => '2',
            'line_regular_number_two'   => '3',
            'line_regular_number_three' => '4',
            'line_regular_number_four'  => '5',
            'line_regular_number_five'  => '6',
            'line_lucky_number_one'     => '1',
            'line_lucky_number_two'     => '2',
            'config'                    => 2
        ]);

        // $I->haveInDatabase('play_configs',$play_config_to_bet->toArray());
        $I->expect('This test pass until 2020-04-07 (castillo doesn\'t accept past dates)');
        $I->wantTo('I want to created the bets from the configurations');
        $I->runShellCommand('php ' . __DIR__ . '/../../apps/cli-test.php bet placeBets 2020-04-06');
        //$I->seeInShellOutput('i dont know');
        $I->expect('There\'s one record in the bets table');
        $I->canSeeNumRecords(1, 'bets');
        $I->expect('I can see in database the correct bet');
        $I->canSeeInDatabase('bets', [
            'id' => 1,
        ]);


    }
}