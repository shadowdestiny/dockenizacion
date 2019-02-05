<?php
/**
 * Created by PhpStorm.
 * User: lmarin
 * Date: 22/01/2019
 * Time: 16:36
 */


use EuroMillions\tests\helpers\builders\UserBuilder;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\components\transaction\detail\TicketPurchaseDetail;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use FunctionalTester;
use Money\Currency;
use Money\Money;

class MegamillionsTransactionsCest
{
    public function _before(FunctionalTester $I)
    {

    }

    public function seeTransactionEurojackpot( FunctionalTester $I )
    {

        $user = $I->setRegisteredUser();
        $userId = $user->getId();

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

        $I->haveInDatabase('lotteries',
            \EuroMillions\tests\helpers\mothers\LotteryMother::aEuroJackpot()->toArray()
        );

        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEurojackpotDrawWithJackpotAndBreakDown()
                ->build()
                ->toArray()
        );


        $I->haveInDatabase('transactions', [
            'id'    => 1,
            'user_id'   => $userId,
            'date'  => '2016-05-10 13:59:06',
            'wallet_before_uploaded_amount'    => '100000000',
            'wallet_before_uploaded_currency_name' => 'EUR',
            'wallet_before_winnings_amount'   => '3000',
            'wallet_before_winnings_currency_name'  => 'EUR',
            'wallet_after_uploaded_amount' => '0',
            'wallet_after_uploaded_currency_name'  => 'EUR',
            'wallet_after_winnings_amount'  => '4000',
            'wallet_after_winnings_currency_name'   => 'EUR',
            'entity_type'     => 'ticket_purchase',
            'data' => '1#1#300#0#1#1#0',
            'transactionID' => '1',
            'wallet_before_subscription_amount' => '0',
            'wallet_before_subscription_currency_name' => 'EUR',
            'wallet_after_subscription_amount' => '0',
            'wallet_after_subscription_currency_name' => 'EUR',
        ]);

        $I->haveInDatabase('play_configs',
            [
                'id'                            => 1,
                'user_id'                       => $userId,
                'active'                        => 1,
                'start_draw_date'               => '2019-01-18',
                'last_draw_date'                => '2019-01-18',
                'frequency'                     => '1',
                'line_regular_number_one'       => '21',
                'line_regular_number_two'       => '22',
                'line_regular_number_three'     => '23',
                'line_regular_number_four'      => '24',
                'line_regular_number_five'      => '25',
                'line_lucky_number_one'         => '1',
                'line_lucky_number_two'         => '2',
                'lottery_id'                    => '5',
                'discount_value'                => '0',
            ]
        );

        $I->haveInDatabase('playconfig_transaction',
            [
                'id'                            => 1,
                'transactionID'                 => 1,
                'playConfig_id'                 => 1,
            ]
        );

        $I->haveInDatabase('bets',
            [
                'id'                                    => 1,
                'euromillions_draw_id'                  => 5,
                'playConfig_id'                         => 1,
                 // null
            ]
        );

         $I->haveInSession('EM_current_user', $userId);
         $I->amOnPage('profile/transactions');
         $I->canSee('Transaction');
         $lottery_name = $I->grabTextFrom('#table_transactions tbody tr td.lottery');
         $I->assertEquals('EuroJackpot', trim($lottery_name));

    }

}