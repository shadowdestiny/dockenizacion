<?php
namespace EuroMillions\services\external_apis;

use EuroMillions\interfaces\IEuroMillionsResultStrategy;

class LotteryDotIeEuroMillionsResultsStrategy implements IEuroMillionsResultStrategy
{
    public function load($xmlString)
    {
        $result = [];
        $xml = new \SimpleXMLElement($xmlString);
        foreach ($xml->DrawResult->Structure->Tier as $category) {
            $result[0][(string)$category->Match]['winners'] = (int)$category->Winners;
            $result[0][(string)$category->Match]['prize'] = (int)$category->Prize;
        }
        foreach ($xml->DrawResult->Numbers->DrawNumber as $number) {
            if ($number->Type == "Standard") {
                $result[1][] = (int)$number->Number;
            } else {
                $result[2][] = (int)$number->Number;
            }
        }
        return $result;
    }
}