<?php


namespace tests\base;


use EuroMillions\vo\EuroMillionsLuckyNumber;
use EuroMillions\vo\EuroMillionsRegularNumber;

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
}