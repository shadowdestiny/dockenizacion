<?php

class HomeCest
{

    public function _before(AcceptanceTester $I)
    {
        sleep(3);
        $I->amOnPage('/');
    }

    public function _after(AcceptanceTester $I)
    {
    }

    /**
     * @param AcceptanceTester $I
     * @group active
     */
    public function seePage(AcceptanceTester $I)
    {
        $I->wantTo('Ensure that frontpage works even if crons did not');
        sleep(1);
        $I->see('Jackpot');
    }
}