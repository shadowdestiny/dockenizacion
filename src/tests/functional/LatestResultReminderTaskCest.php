<?php


use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;
use EuroMillions\tests\helpers\mothers\EuroMillionsLineMother;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\UserMother;

class LatestResultReminderTaskCest
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
    public function tryToTest(FunctionalTester $I)
    {
        /** @var \EuroMillions\web\entities\User $user */
        $user = UserMother::aUserWith50Eur()->build();
        $draw = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->withJackpot(new \Money\Money((int) 15000000, new \Money\Currency('EUR')))->build();
        $playConfig = PlayConfigMother::aPlayConfigSetForUser($user)
            ->withLine(EuroMillionsLineMother::anEuroMillionsLine())
            ->withStartDrawDate(new DateTime('2016-01-01'))
            ->withLastDrawDate(new DateTime('2016-05-01'))
            ->build();

//        $lottery = [
//            'id' => '1',
//            'name' => 'EuroMillions',
//            'active' => '1',
//            'frequency' => '0010010',
//            'jackpot_api' => '',
//            'result_api' => '',
//            'draw_time' => '20:00',
//            'single_bet_price_amount' => '2500',
//            'single_bet_price_currency_name' => 'EUR'
//        ];

        $userNotifications = [
            'id' => 1,
            'user_id' => '9098299B-14AC-4124-8DB0-19571EDABE55',
            'notification_id' => 4,
            'active' => 1,
            'type_config_value' => 0
        ];
        $I->haveInDatabase('users',$user->toArray());
        $I->haveInDatabase('user_notifications', $userNotifications);
       // $I->haveInDatabase('lotteries', $lottery);
        $I->haveInDatabase('euromillions_draws', $draw->toArray());
        $I->haveInDatabase('play_configs', $playConfig->toArray());
        $I->wantTo('Send email reminder with results');
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php latestresult resultsReminderWhenPlayed 2016-04-22');
    }

}
