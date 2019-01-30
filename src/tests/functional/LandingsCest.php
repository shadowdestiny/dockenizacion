<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 29/01/19
 * Time: 08:21 PM
 */

use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;

/**
 * Class LandingsCest
 */
class LandingsCest
{
    /**
     * functionalcase EuromillionsLandingOnLoad
     * @param FunctionalTester $I
     */
    public function EuromillionsLandingOnLoad(FunctionalTester $I)
    {
        $draw = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->withJackpot(new \Money\Money((int) 12000000000, new \Money\Currency('EUR')))->withDrawDate(new \DateTime('tomorrow'))->build();
        $draw_array = $draw->toArray();
        $I->haveInDatabase('euromillions_draws', $draw_array);
        $I->amOnPage('/landings/euromillions/');
        $I->canSee('€120M');
        $diff= explode(':', (new \DateTime())->diff((new \DateTime('tomorrow'))->modify('-1 hours'))->format('%a:%h:%i'));
        $I->canSee($diff[2].'M');
    }

    /**
     * functionalcase PowerBallLandingOnLoad
     * @param FunctionalTester $I
     */
    public function PoweBallLandingOnLoad(FunctionalTester $I)
    {
        $draw = EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()->withJackpot(new \Money\Money((int) 12000000000, new \Money\Currency('EUR')))->withDrawDate(new \DateTime('tomorrow'))->build();
        $draw_array = $draw->toArray();
        $I->haveInDatabase('euromillions_draws', $draw_array);
        $I->amOnPage('/landings/powerball/');
        $I->canSee('€120M');
        $diff= explode(':', (new \DateTime())->diff((new \DateTime('tomorrow'))->modify('-1 hours'))->format('%a:%h:%i'));
        $I->canSee($diff[2].'M');
    }

    /**
     * functionalcase MegaMillionsLandingOnLoad
     * @param FunctionalTester $I
     */
    public function MegaMillionsLandingOnLoad(FunctionalTester $I)
    {
        $draw = EuroMillionsDrawMother::anMegaMillionsDrawWithJackpotAndBreakDown()->withJackpot(new \Money\Money((int) 12000000000, new \Money\Currency('EUR')))->withDrawDate(new \DateTime('tomorrow'))->build();
        $draw_array = $draw->toArray();
        $I->haveInDatabase('euromillions_draws', $draw_array);
        $I->amOnPage('/landings/megamillions/');
        $I->canSee('€120M');
        $diff= explode(':', (new \DateTime())->diff((new \DateTime('tomorrow'))->modify('-1 hours'))->format('%a:%h:%i'));
        $I->canSee($diff[2].'M');
    }

    /**
     * functionalcase MegaMillionsLandingOnLoadAddUser
     * @param FunctionalTester $I
     */
    public function MegaMillionsLandingOnLoadAddUser(FunctionalTester $I)
    {
        $draw = EuroMillionsDrawMother::anMegaMillionsDrawWithJackpotAndBreakDown()->withJackpot(new \Money\Money((int) 12000000000, new \Money\Currency('EUR')))->withDrawDate(new \DateTime('tomorrow'))->build();
        $draw_array = $draw->toArray();
        $I->haveInDatabase('euromillions_draws', $draw_array);
        $I->amOnPage('/landings/megamillions/form');
        $I->canSee('€120M');
        $diff= explode(':', (new \DateTime())->diff((new \DateTime('tomorrow'))->modify('-1 hours'))->format('%a:%h:%i'));
        $I->canSee($diff[2].'M');
        $I->submitForm(
            '#sign-up-form', [
                'name' => 'Test',
                'surname' => 'Test1',
                'email' => 'prueba@gmail.com',
                'password' => '12345678',
                'confirm_password' => '12345678',
                'day' => '06',
                'month' => 'May',
                'year' => '1985',
                'country' => 'Colombia',
                'prefix' => '1234',
                'phone' => '123456789',
                'accept' => '1'
            ]
        );
        $I->canSeeInDatabase('user', ['name' => 'Test']);
    }
}