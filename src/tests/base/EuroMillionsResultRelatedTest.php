<?php


namespace tests\base;


use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\EuroMillionsLuckyNumber;
use EuroMillions\vo\EuroMillionsRegularNumber;
use EuroMillions\vo\LastDrawDate;
use EuroMillions\vo\PlayFormToStorage;

trait EuroMillionsResultRelatedTest {

    protected function getRegularNumbers(array $numbers)
    {
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsRegularNumber($number);
        }
        return $result;
    }
    protected function getLuckyNumbers(array $numbers)
    {
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsLuckyNumber($number);
        }
        return $result;
    }

    protected function getPlayFormToStorage()
    {
        $frequency = 1;
        $startDrawDate = '2015-09-18';
        $lastDrawDate = new LastDrawDate($startDrawDate,$frequency);

        $playFormToStorage = new PlayFormToStorage();
        $playFormToStorage->startDrawDate = $startDrawDate;
        $playFormToStorage->frequency = $startDrawDate;
        $playFormToStorage->lastDrawDate = $lastDrawDate->getLastDrawDate();
        $playFormToStorage->drawDays = 2;
        $playFormToStorage->euroMillionsLine = $this->getEuroMillionsLine();

        return $playFormToStorage;
    }

    /**
     * @return array
     */
    protected function getEuroMillionsLine()
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];

        $r_numbers = $this->getRegularNumbers($regular_numbers);
        $l_numbers = $this->getLuckyNumbers($lucky_numbers);

        $euroMillionsLine = [
            new EuroMillionsLine($r_numbers,$l_numbers),
            new EuroMillionsLine($r_numbers,$l_numbers),
            new EuroMillionsLine($r_numbers,$l_numbers)
        ];
        return $euroMillionsLine;
    }



}