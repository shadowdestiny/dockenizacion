<?php


namespace EuroMillions\tests\functional;


use EuroMillions\shared\vo\RedisOrderKey;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\DepositTransaction;
use FunctionalTester;
use Money\Currency;
use Money\Money;

class NotificationCest
{

    public function _before(FunctionalTester $I)
    {

        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
                ->withId(10)
                ->withDrawDate(new \DateTime('2020-01-10'))
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->build()->toArray()
        );
        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->withLottery(
                \EuroMillions\tests\helpers\mothers\LotteryMother::aPowerBall()
            )
                ->withId(4)
                ->withDrawDate(new \DateTime('2020-01-12'))
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->withJackpot(new Money(4000000000, new Currency('EUR')))
                ->build()->toArray()
        );
        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->withLottery(
                \EuroMillions\tests\helpers\mothers\LotteryMother::aMegaMillions()
            )
                ->withId(5)
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->withDrawDate(new \DateTime('2020-01-11'))
                ->withJackpot(new Money(5000000000, new Currency('EUR')))
                ->build()->toArray()
        );
        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
                ->withId(6)
                ->withDrawDate(new \DateTime('2018-01-10'))
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->build()->toArray()
        );
        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->withLottery(
                \EuroMillions\tests\helpers\mothers\LotteryMother::aPowerBall()
            )
                ->withId(7)
                ->withDrawDate(new \DateTime('2018-01-12'))
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->withJackpot(new Money(4000000000, new Currency('EUR')))
                ->build()->toArray()
        );
        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->withLottery(
                \EuroMillions\tests\helpers\mothers\LotteryMother::aMegaMillions()
            )
                ->withId(8)
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->withDrawDate(new \DateTime('2018-01-11'))
                ->withJackpot(new Money(5000000000, new Currency('EUR')))
                ->build()->toArray()
        );
    }

    /**
     * @param FunctionalTester $I
     * @group notifications
     */
    public function canSeeNewTicketPurchaseAndUpdatedDepositTransaction(FunctionalTester $I)
    {
        $date = new \DateTime();
        $orderJson = '{"total":0,"fee":35,"fee_limit":1200,"single_bet_price":"300","num_lines":1,"play_config":[{"id":1,"startDrawDate":"2020-01-10 00:00:00","lastDrawDate":"2020-01-10 00:00:00","frequency":1,"euromillions_line":[{"regular":[6,16,34,36,44],"lucky":[3,12]}],"user":{"id":"9098299B-14AC-4124-8DB0-19571EDABE55"},"powerPlay":null}],"lottery":"EuroMillions"}';

        /** @var \EuroMillions\web\entities\User $user */
        $user = UserMother::aUserWith50Eur()->build();

        $I->haveInDatabase('users', $user->toArray());
        $I->haveInRedis('string','test_PlayStore_EMORDER:'.RedisOrderKey::create(strtolower($user->getId()),1)->key(), $orderJson);
        $data = [
            'lottery_id' => 1,
            'lotteryName' => 'EuroMillions',
            'numBets' => 3,
            'amount' =>  2000,
            'amountWithWallet' => 2000,
            'amountWithCreditCard' => 0,
            'feeApplied' => 0,
            'user' => $user,
            'walletBefore' => new Wallet(),
            'walletAfter' => new Wallet(),
            'transactionID' => '123456',
            'now' => $date,
            'playConfigs' => [1,2],
            'discount' => 0,
            'status' => 'PENDING',
            'withWallet' => 0
        ];
        $depositTransaction= new DepositTransaction($data);
        $depositTransaction->toString();
        $I->haveInDatabase('transactions',
            [
                'id' => 1,
                'user_id' => $user->getId(),
                'date' => '2019-02-13',
                'wallet_before_uploaded_amount' => 0,
                'wallet_before_uploaded_currency_name' => 0,
                'wallet_before_winnings_amount' => 0,
                'wallet_before_winnings_currency_name' => 0,
                'wallet_after_uploaded_amount' => 300,
                'wallet_after_uploaded_currency_name' => 0,
                'wallet_after_winnings_amount' => 0,
                'wallet_after_winnings_currency_name' => 0,
                'entity_type' => 'deposit',
                'data' => $depositTransaction->getData(),
                'transactionID' => '123456',
                'wallet_before_subscription_amount' => 0,
                'wallet_before_subscription_currency_name' => 0,
                'wallet_after_subscription_amount' => 0,
                'wallet_after_subscription_currency_name' => 0,
                'message' => ""
            ]
        );

        $I->amOnPage("/paymentmx/notification?transaction=123456&status=SUCCESS&date=2020-01-10");
        $I->canSeeInDatabase('transactions', ['data' => '1#335#SUCCESS#1#EuroMillions#0']);
    }

}