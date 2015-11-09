<?php
namespace EuroMillions\web\services\external_apis;

use EuroMillions\web\interfaces\IResultStrategy;

class LotteryDotIeEuroMillionsResultsStrategy implements IResultStrategy
{
    public function load($xmlString)
    {
        $result = [];
        $xml = new \SimpleXMLElement($xmlString);
        foreach ($xml->DrawResult->Structure->Tier as $category) {
            $result['categories'][(string)$category->Match]['winners'] = (int)$category->Winners;
            $result['categories'][(string)$category->Match]['prize'] = (int)$category->Prize;
        }
        foreach ($xml->DrawResult->Numbers->DrawNumber as $number) {
            if ($number->Type == "Standard") {
                $result['regular_numbers'][] = (int)$number->Number;
            } else {
                $result['lucky_numbers'][] = (int)$number->Number;
            }
        }
        return $result;
    }
}