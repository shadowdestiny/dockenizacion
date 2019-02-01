<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 24/01/19
 * Time: 01:18 PM
 */

use Money\Money;
use Money\Currency;
use FunctionalTester;
use EuroMillions\tests\helpers\mothers\UserMother;

class PowerBallCartProfileCest
{
    public function _before(FunctionalTester $I)
    {
        $aPowerBall = \EuroMillions\tests\helpers\mothers\LotteryMother::aPowerBall();

        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
                ->withLottery($aPowerBall)
                ->withId(1)
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->withDrawDate(new \DateTime('2020-01-8'))
                ->withJackpot(new Money(5000000000, new Currency('EUR')))
                ->build()->toArray()
        );

        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
                ->withLottery($aPowerBall)
                ->withId(2)
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->withDrawDate(new \DateTime('2020-01-11'))
                ->withJackpot(new Money(5000000000, new Currency('EUR')))
                ->build()->toArray()
        );
    }

    /**
     * @group powerball
     * @param FunctionalTester $I
     */
    public function canSeeUserRegisteredWhenFillPowerBallSignUpForm(FunctionalTester $I)
    {
        $I->amOnPage('/powerball/cart/profile');

        $I->submitForm(
            '#sign-up-form', [
                'name' => 'Test',
                'surname' => 'Test1',
                'email' => 'test_pb@euromillions.com',
                'password' => '123456',
                'confirm_password' => '123456',
                'day' => '29',
                'month' => '10',
                'year' => '1980',
                'country' => '204',
                'prefix' => '34',
                'phone' => '600000000',
                'accept' => true
            ]
        );

        $I->canSeeInDatabase('users', ['email' => 'test_pb@euromillions.com']);
    }

    /**
     * @group powerball
     * @param FunctionalTester $I
     */
    public function canSeeLoggedCookieWhenSubmitPowerBallLoginForm(FunctionalTester $I)
    {
        $user = UserMother::aJustRegisteredUser()->build();
        $I->haveInDatabase('users',
            $user->toArray()
        );
        $I->amOnPage('/powerball/cart/profile');
        $I->fillField(['name' => 'email'], 'nonexisting@email.com');
        $I->fillField(['name' => 'password'], 'Password01');
        $I->click('#go');
        $I->haveInSession('EM_logged_user', $user->getId());
    }
}
