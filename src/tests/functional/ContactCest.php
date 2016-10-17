<?php

/**
 * Class ContactCest
 */
class ContactCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/contact');
    }

    /**
     * @group active
     * @param FunctionalTester $I
     */
    public function seePage(FunctionalTester $I)
    {
        $I->wantTo('I can see contact page');
        $I->canSee('Contact us');
    }

    /**
     * @group active
     * @param FunctionalTester $I
     */
    public function sendFormWithoutCaptcha(FunctionalTester $I)
    {
        $I->wantTo('It should show an error message because captcha it was unchecked.');
        $I->selectOption('form select[name=topic]', '1');
        $I->fillField(['name' => 'fullname'], 'TestName');
        $I->fillField(['name' => 'email'], 'jon@mail.com');
        $I->fillField(['name' => 'message'], 'Test Message');
        $I->click( ['id'=>'submitBtn'] );
        $I->see('You are a robot... or you forgot to check the Captcha verification.');
    }

}
