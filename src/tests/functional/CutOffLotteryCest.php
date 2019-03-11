<?php

use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;

/**
 * Class CutOffLotteryCest
 */
class CutOffLotteryCest
{
    public function _before(FunctionalTester $I)
    {
        $draw = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()->withJackpot(new \Money\Money((int) 12000000000, new \Money\Currency('EUR')))->withId(1)->withDrawDate(new \DateTime('today'))->build();
        $draw_array = $draw->toArray();
        $I->haveInDatabase('euromillions_draws', $draw_array);

        $draw = EuroMillionsDrawMother::anEuroJackpotDrawWithJackpotAndBreakDown()->withJackpot(new \Money\Money((int) 12000000000, new \Money\Currency('EUR')))->withId(2)->withDrawDate(new \DateTime('today'))->build();
        $draw_array = $draw->toArray();
        $I->haveInDatabase('euromillions_draws', $draw_array);


        $I->updateInDatabase('lotteries',['name' => 'EuroMillions'], ['frequency' => $this->getFrecuency(), 'draw_time' => $this->getTime()]);
        $I->updateInDatabase('lotteries',['name' => 'EuroJackpot'], ['frequency' => $this->getFrecuency(), 'draw_time' => $this->getTime()]);
    }

    public function _after(FunctionalTester $I)
    {

    }

    /**
     * functionalcase checkCutOfLotteryEuroJackpot
     * @param FunctionalTester $I
     */
    public function checkCutOfLotteryEuroJackpot(FunctionalTester $I)
    {
        $I->amOnPage('/eurojackpot/play');
        $I->canSeeElement('#closeticket');
    }

    /**
     * functionalcase checkCutOfLotteryEuroMillions
     * @param FunctionalTester $I
     */
    public function checkCutOfLotteryEuroMillions(FunctionalTester $I)
    {
        $I->amOnPage('/euromillions/play');
        $I->canSeeElement('#closeticket');
    }

    private function getFrecuency()
    {
        $frecuency='w';
        $compare=date('w');
        for($i=1; $i<7; $i++)
        {
            if($compare==$i)
            {
                $frecuency.='1';
            }
            else
            {
                $frecuency.='0';
            }
        }

        if($compare==0)
        {
            $frecuency.='1';
        }

        return $frecuency;
    }

    private function getTime()
    {
        return date('H:i:s', strtotime('+1800 seconds'));
    }
}