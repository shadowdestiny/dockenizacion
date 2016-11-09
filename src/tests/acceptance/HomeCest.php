<?php

class HomeCest
{

    private $zap;

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
//        $I->wantTo('See home page');
//        $risks = $this->getCountRiskMedium();
//        $I->assertEquals(0, count($risks));
//        $I->see('Jackpot');
    }

    private function getCountRiskMedium()
    {
        $this->zap = new \Zap\Zapv2('tcp://127.0.0.1:8090');
        $alerts = $this->zap->core->alerts();
        $highRisk = [];
        $alertRepeatead = false;
        foreach($alerts as $alert) {
            if($alert['risk'] != 'Low') {
                if(count($highRisk) > 0) {
                    foreach($highRisk as $risk) {
                        if($risk['alert'] == $alert['alert']) {
                            $alertRepeatead = true;
                        }
                    }
                }
                if(!$alertRepeatead) {
                    $highRisk[] = $alert;
                    $alertRepeatead=false;
                }
            }
        }
        return $highRisk;

    }
}