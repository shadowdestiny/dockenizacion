<?php
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\UserMother;

class BetTaskCest
{

    public function _before(FunctionalTester $I)
    {

    }


    public function _after(FunctionalTester $I)
    {

    }

    /**
     * @param FunctionalTester $I
     * @group active
     */
    public function betsAreCreatedTest(FunctionalTester $I)
    {
        $user = UserMother::aUserWith500Eur()->build();
        $I->haveInDatabase('users', $user->toArray());

        $play_config_to_bet = PlayConfigMother::aPlayConfig();
        $play_config_to_bet->withStartDrawDate(new \DateTime('2020-04-01'));
        $play_config_to_bet->withLastdrawDate(new \DateTime('2020-04-30'));
        $play_config_to_bet->withLottery(2);
        $play_config_to_bet->withUser($user);

        $I->haveInDatabase('lotteries',[
            'id' => 2,
            'name' => 'EuroMillions2',
            'active' => 1,
            'frequency' => 'w0100100',
            'jackpot_api' => 'LoteriasyapuestasDotEs',
            'result_api' => 'LoteriasyapuestasDotEs',
            'draw_time' => '20:00:00',
            'single_bet_price_amount' => 250,
            'single_bet_price_currency_name' => 'EUR'
        ]);

        $I->haveInDatabase('euromillions_draws', [
            'id'         => 1,
            'lottery_id' => 2,
            'draw_date'  => '2020-04-07',
        ]);


        $I->haveInDatabase('play_configs', $play_config_to_bet->toArray());

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

    /**
     * @param FunctionalTester $I
     * @group unic
     */
    public function betsAreCreatedWithDiscountTest(FunctionalTester $I)
    {
        $I->haveInDatabase('lotteries',[
            'id' => 2,
            'name' => 'EuroMillions2',
            'active' => 1,
            'frequency' => 'w0100100',
            'jackpot_api' => 'LoteriasyapuestasDotEs',
            'result_api' => 'LoteriasyapuestasDotEs',
            'draw_time' => '20:00:00',
            'single_bet_price_amount' => 250,
            'single_bet_price_currency_name' => 'EUR'
        ]);


        $I->haveInDatabase('euromillions_draws', [
            'id'         => 1,
            'lottery_id' => 2,
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
            'frequency'                 => 48,
            'line_regular_number_one'   => '2',
            'line_regular_number_two'   => '3',
            'line_regular_number_three' => '4',
            'line_regular_number_four'  => '5',
            'line_regular_number_five'  => '6',
            'line_lucky_number_one'     => '1',
            'line_lucky_number_two'     => '2',
            'lottery_id'                => 2,
        ]);

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

        //Comprobar amount antes y dsps, se tiene q hacer la jugada con descuento
    }
}
