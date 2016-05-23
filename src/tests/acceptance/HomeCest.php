<?php

class HomeCest
{

    private $zap;

    public function _before(AcceptanceTester $I)
    {
//        $this->zap = new \Zap\Zapv2('tcp://127.0.0.1:8090');
//        $version = $this->zap->core->version();
//        if (is_null($version)) {
//            echo "PHP API error\n";
//            exit();
//        } else {
//            echo "version: ${version}\n";
//        }
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