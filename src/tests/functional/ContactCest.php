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

    public function seePage(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->wantTo('Ensure that frontpage works even if crons did not');
        $I->canSee('Contact us');
    }

    public function sendButton(FunctionalTester $I)
    {
        $I->wantTo('Be able to send the message');
        $I->canSeeLink('Send message', '/contact');
    }

    public function sendFormWithoutCaptcha(FunctionalTester $I)
    {
        $I->selectOption('form input[name=topic]', '1');
        $I->fillField(['name' => 'fullname'], 'TestName');
        $I->fillField(['name' => 'email'], 'jon@mail.com');
        $I->fillField(['name' => 'message'], 'Test Message');
        $I->click('Submit');
        $I->see('You are a robot... or you forgot to check the Captcha verification.');
    }

}
