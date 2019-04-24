<?php
use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;
use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use Money\Currency;
use Money\Money;
use Phalcon\Di;

/**
 * Class JackpotTaskCest
 */
class JackpotTaskCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function jackpotValitionRound(FunctionalTester $I){
        $I->wantTo('Validation play value draw');

        $I->haveInDatabase('trackingCodes',
            [
                'id'                    => 1,
                'name'                  => 'test',
                'description'           => 'test',
            ]
        );

        $I->haveInDatabase('languages',
            [
                'id'                => 1,
                'ccode'             => 'en',
                'active'            => 1,
                'defaultLocale'     => 'en_US',
            ]
        );

        $I->haveInDatabase('lotteries', \EuroMillions\tests\helpers\mothers\LotteryMother::aMegaMillions()->toArray());
        $I->haveInDatabase('lotteries', \EuroMillions\tests\helpers\mothers\LotteryMother::anEuroMillions()->toArray());
        $I->haveInDatabase('lotteries', \EuroMillions\tests\helpers\mothers\LotteryMother::aEuroJackpot()->toArray());
        $I->haveInDatabase('lotteries', \EuroMillions\tests\helpers\mothers\LotteryMother::aPowerBall()->toArray());
        $I->haveInDatabase('lotteries', \EuroMillions\tests\helpers\mothers\LotteryMother::aMegaSena()->toArray());

        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
                ->withDrawDate(new \DateTime())
                ->withJackpot(new Money((int) round(6682079997.86 / 1000000) * 100000000, new Currency('EUR')))
                ->build()
                ->toArray()
        );

        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()
                ->withDrawDate(new \DateTime())
                ->withJackpot(new Money((int) round(3600000000 / 1000000) * 100000000, new Currency('EUR')))
                ->build()
                ->toArray()
        );

        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()
                ->withLottery(LotteryMother::aMegaMillions())
                ->withJackpot(new Money((int) round(6682079997.86 / 1000000) * 100000000, new Currency('EUR')))
                ->withId(4)
                ->withDrawDate(new \DateTime())
                ->build()
                ->toArray()
        );

        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()
                ->withLottery(LotteryMother::aEuroJackpot())
                ->withJackpot(new Money((int) round(1900000000 / 1000000) * 100000000, new Currency('EUR')))
                ->withId(5)
                ->withDrawDate(new \DateTime())
                ->build()
                ->toArray()
        );


        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()
                ->withLottery(LotteryMother::aMegaSena())
                ->withJackpot(new Money((int) round(200000000 / 1000000) * 100000000, new Currency('EUR')))
                ->withId(6)
                ->withDrawDate(new \DateTime())
                ->build()
                ->toArray()
        );

        $current_rate_usd = 1.124037; //actual rate
        $unit = 10000;

        $format = function($jackpot){
            return "$".preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents(round($jackpot)));
        };

        $I->expect('The Jackpot amount is greater than the minimum amount for the lottery');
        $jackpot_amount_euromillions    = ($I->grabFromDatabase('euromillions_draws', 'jackpot_amount', ['id'=>1]) * $current_rate_usd) / (1000000 * $unit);
        $jackpot_amount_powerball       = ($I->grabFromDatabase('euromillions_draws', 'jackpot_amount', ['id'=>3])* $current_rate_usd) / (1000000 * $unit);
        $jackpot_amount_megamillions    = ($I->grabFromDatabase('euromillions_draws', 'jackpot_amount', ['id'=>4])* $current_rate_usd) / (1000000 * $unit);
        $jackpot_amount_eurojackpot     = ($I->grabFromDatabase('euromillions_draws', 'jackpot_amount', ['id'=>5])* $current_rate_usd) / (1000000 * $unit);
        $jackpot_amount_megasena        = ($I->grabFromDatabase('euromillions_draws', 'jackpot_amount', ['id'=>6])* $current_rate_usd) / (1000000 * $unit);

        $I->assertEquals("$75", $format($jackpot_amount_euromillions));
        $I->assertEquals("$40", $format($jackpot_amount_powerball));
        $I->assertEquals("$75", $format($jackpot_amount_megamillions));
        $I->assertEquals("$21", $format($jackpot_amount_eurojackpot));
        $I->assertEquals("$2", $format($jackpot_amount_megasena));
    }

    public function jackpotValidatorOfCache(FunctionalTester $I){
        $I->wantTo('Update the Jackpot for next draw');

        $I->haveInDatabase('trackingCodes',
            [
                'id'                    => 1,
                'name'                  => 'prueba',
                'description'           => 'prueba',
            ]
        );

        $I->haveInDatabase('languages',
            [
                'id'                => 1,
                'ccode'             => 'en',
                'active'            => 1,
                'defaultLocale'     => 'en_US',
            ]
        );

        $I->haveInDatabase('lotteries', \EuroMillions\tests\helpers\mothers\LotteryMother::aMegaMillions()->toArray());
        $I->haveInDatabase('lotteries', \EuroMillions\tests\helpers\mothers\LotteryMother::anEuroMillions()->toArray());
        $I->haveInDatabase('lotteries', \EuroMillions\tests\helpers\mothers\LotteryMother::aEuroJackpot()->toArray());
        $I->haveInDatabase('lotteries', \EuroMillions\tests\helpers\mothers\LotteryMother::aPowerBall()->toArray());
        $I->haveInDatabase('lotteries', \EuroMillions\tests\helpers\mothers\LotteryMother::aMegaSena()->toArray());

        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
                ->withDrawDate(new \DateTime())
                ->withJackpot(new Money(5000000000, new Currency('EUR')))
                ->build()
                ->toArray()
        );

        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()
                ->withDrawDate(new \DateTime())
                ->withJackpot(new Money(6000000000, new Currency('EUR')))
                ->build()
                ->toArray()
        );

        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()
                ->withLottery(LotteryMother::aMegaMillions())
                ->withJackpot(new Money(7000000000, new Currency('EUR')))
                ->withId(4)
                ->withDrawDate(new \DateTime())
                ->build()
                ->toArray()
        );

        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()
                ->withLottery(LotteryMother::aEuroJackpot())
                ->withJackpot(new Money(8000000000, new Currency('EUR')))
                ->withId(5)
                ->withDrawDate(new \DateTime())
                ->build()
                ->toArray()
        );


        $I->haveInDatabase(
            'euromillions_draws',
            EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()
                ->withLottery(LotteryMother::aMegaSena())
                ->withJackpot(new Money(9000000000, new Currency('EUR')))
                ->withId(6)
                ->withDrawDate(new \DateTime())
                ->build()
                ->toArray()
        );

        $entity_manager = Di::getDefault()->get('entityManager');
        $sut = $entity_manager->getRepository('EuroMillions\web\entities\EuroMillionsDraw');
        $actual = $sut->giveMeLotteriesOrderedByHeldDate();

        $simulation = function($new_amount, $lottery_name, $key, $draw) use ($I){

            $I->wantTo($lottery_name. ': crohn run simulation');

            $I->updateInDatabase('euromillions_draws',array('jackpot_amount' => $new_amount),  array('id' => $draw->getId()));
            $I->runShellCommand('php '.__DIR__.'/../../apps/cli.php clear-cache clear');

            #try{ $I->amOnPage('DevOps/clearapc'); } catch (\Exception $e){ $I->wantTo('clean cache!'); }

            $amount = $draw->getJackpot()->getAmount();
            $I->wantTo('previous amount: '.$amount);
            $I->wantTo('new amount: '.$new_amount);

            $entity_manager = Di::getDefault()->get('entityManager');
            $sut = $entity_manager->getRepository('EuroMillions\web\entities\EuroMillionsDraw');

            // cached error
            $I->assertEquals(
                $sut->giveMeLotteriesOrderedByHeldDate()[$key]->getJackpot()->getAmount(),
                $new_amount
            );
        };

        foreach ($actual as $key => $draw){
            switch ($draw->getLottery()->getId()){
                case 1:
                    break;
                case 2:
                    break;
                case 3:
                    // powerball: crohn run simulation
                    $amount_to_update = '1000000002';
                    $simulation($amount_to_update,"powerball",$key,$draw);
                    break;
                case 4:
                    break;
                case 5:
                    break;
                case 6:
                    // megasena: crohn run simulation
                    $amount_to_update = '1000000001';
                    $simulation($amount_to_update,"megasena",$key,$draw);
                    break;
            }
        }
    }

    private function clearAction()
    {
        /** @var \Redis $redis */
        $redis = $this->domainServiceFactory->getServiceFactory()->getDI()->get('redisCache');
        $redis_cache = new RedisCache();
        $redis_cache->setRedis($redis);
        $results = $redis->keys('result_*');
        foreach($results as $result){
            $redis->delete($result);
        }

        apc_clear_cache();
        apc_clear_cache('user');
    }

    // tests
    public function jackpotIsUpdatedTest(FunctionalTester $I, $scenario)
    {
        $I->wantTo('Update the Jackpot for next draw');
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php jackpot update');
        $I->expect('There\'s one record in the draws table');
        $I->canSeeNumRecords(1, 'euromillions_draws');
        $I->expect('The Jackpot amount is greater than the minimum amount for the lottery');
        $jackpot_amount = $I->grabFromDatabase('euromillions_draws', 'jackpot_amount');
        $I->assertGreaterThan(15000000, $jackpot_amount);
    }

    /**
     * @param FunctionalTester $I
     * @group problems
     */
    public function jackpotReminderWhenThersholdReach( FunctionalTester $I)
    {
        /** @var \EuroMillions\web\entities\User $user */
        $user = UserMother::aUserWith50Eur()->build();
        $userNotifications = [
            'id' => 1,
            'user_id' => '9098299B-14AC-4124-8DB0-19571EDABE55',
            'notification_id' => 1,
            'active' => 1,
            'type_config_value' => 1200000
        ];
        $I->haveInDatabase('users',$user->toArray());
        $I->haveInDatabase('user_notifications', $userNotifications);
        $draw = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->withJackpot(new \Money\Money((int) 15000000, new \Money\Currency('EUR')))->build();
        $draw_array = $draw->toArray();
        $I->haveInDatabase('euromillions_draws', $draw_array);
        $I->wantTo('Send email reminder whthreshold reach');
        $I->runShellCommand('php '.__DIR__.'/../../apps/cli-test.php jackpot reminderJackpot');
    }
}
