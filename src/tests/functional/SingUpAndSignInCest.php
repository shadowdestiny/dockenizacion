<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 20/01/19
 * Time: 11:09
 */

namespace EuroMillions\tests\functional;


use Doctrine\ORM\Query\Expr\Func;
use EuroMillions\tests\helpers\mothers\UserMother;
use FunctionalTester;
use Money\Currency;
use Money\Money;

class SingUpAndSignInCest
{

    public function _before(FunctionalTester $I)
    {
        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
                ->withId(3)
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
        $I->haveInDatabase(
            'euromillions_draws',
            \EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
                ->withId(9)
                ->withDrawDate(new \DateTime('2019-01-10'))
                ->withRaffle(new \EuroMillions\web\vo\Raffle('ABC'))
                ->build()->toArray()
        );
    }

    public function _after(FunctionalTester $I)
    {

    }

    /**
     * @group signup
     * @param FunctionalTester $I
     */
    public function canSeeUserRegisteredWhenFillSignUpForm(FunctionalTester $I)
    {
        $I->amOnPage('/sign-up');
        $I->submitForm(
          '#goSignUp', [
              'name' => 'Test',
              'surname' => 'Test1',
              'email' => 'test@euromillions.com',
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
        $I->canSeeInDatabase('users', ['email' => 'test@euromillions.com']);
    }

    /**
     * @group signup
     * @param FunctionalTester $I
     */
    public function canSeeLoggedCookieWhenSubmitLoginForm(FunctionalTester $I)
    {
        $user = UserMother::aJustRegisteredUser()->build();
        $I->haveInDatabase('users',
                                  $user->toArray()
        );
        $I->amOnPage('/sign-in');
        $I->fillField(['name' => 'email'], 'nonexisting@email.com');
        $I->fillField(['name' => 'password'], 'Password01');
        $I->click('.submit-row');
        $I->haveInSession('EM_logged_user', $user->getId());

    }

    /**
     * @group signup
     * @param FunctionalTester $I
     */
    public function canNotSeeLoggedCookieWhenLogoutClick(FunctionalTester $I)
    {
    }

}