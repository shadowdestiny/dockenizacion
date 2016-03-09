<?php
namespace EuroMillions\tests\base;

trait LotteryDotIeEuromillionsRelatedTest
{
    protected $apiResult = <<<'EOD'
<?xml version="1.0" encoding="utf-8"?>
<ArrayOfDrawResult xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.lottery.ie/resultsservice">
  <DrawResult>
    <DrawName>EuroMillions</DrawName>
    <DrawDate>2015-05-19T19:30:00+01:00</DrawDate>
    <DrawNumber>765</DrawNumber>
    <Message>There was no winner of the EuroMillions jackpot.</Message>
    <PromoMessage1 />
    <PromoMessage2 />
    <TopPrize>21489110</TopPrize>
    <NextDrawDate>2015-05-22T19:30:00+01:00</NextDrawDate>
    <Structure>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>0</Winners>
        <Match>5+2</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>21489110</Prize>
        <IrishWinners>0</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>4</Winners>
        <Match>5+1</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>243342</Prize>
        <IrishWinners>0</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>6</Winners>
        <Match>5</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>54076</Prize>
        <IrishWinners>0</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>22</Winners>
        <Match>4+2</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>7374</Prize>
        <IrishWinners>0</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>472</Winners>
        <Match>4+1</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>301</Prize>
        <IrishWinners>16</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>966</Winners>
        <Match>4</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>147</Prize>
        <IrishWinners>30</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>1091</Winners>
        <Match>3+2</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>93</Prize>
        <IrishWinners>41</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>17459</Winners>
        <Match>2+2</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>27</Prize>
        <IrishWinners>424</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>22122</Winners>
        <Match>3+1</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>20</Prize>
        <IrishWinners>560</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>47390</Winners>
        <Match>3</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>16</Prize>
        <IrishWinners>1163</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>99729</Winners>
        <Match>1+2</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>13</Prize>
        <IrishWinners>2202</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>366744</Winners>
        <Match>2+1</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>10</Prize>
        <IrishWinners>8412</IrishWinners>
      </Tier>
      <Tier xsi:type="EuroMillionsTier">
        <Winners>798965</Winners>
        <Match>2</Match>
        <PrizeType>Cash</PrizeType>
        <Prize>5</Prize>
        <IrishWinners>17471</IrishWinners>
      </Tier>
    </Structure>
    <Numbers>
      <DrawNumber>
        <Number>26</Number>
        <Type>Standard</Type>
      </DrawNumber>
      <DrawNumber>
        <Number>30</Number>
        <Type>Standard</Type>
      </DrawNumber>
      <DrawNumber>
        <Number>31</Number>
        <Type>Standard</Type>
      </DrawNumber>
      <DrawNumber>
        <Number>35</Number>
        <Type>Standard</Type>
      </DrawNumber>
      <DrawNumber>
        <Number>37</Number>
        <Type>Standard</Type>
      </DrawNumber>
      <DrawNumber>
        <Number>8</Number>
        <Type>LuckyStar</Type>
      </DrawNumber>
      <DrawNumber>
        <Number>11</Number>
        <Type>LuckyStar</Type>
      </DrawNumber>
    </Numbers>
  </DrawResult>
</ArrayOfDrawResult>
EOD;

    protected $expectedCategories = [
        "5+2" => ["winners" => 0,
                  "prize"   => 21489110],
        "5+1" => ["winners" => 4,
                  "prize"   => 243342],
        "5"   => ["winners" => 6,
                  "prize"   => 54076],
        "4+2" => ["winners" => 22,
                  "prize"   => 7374],
        "4+1" => ["winners" => 472,
                  "prize"   => 301],
        "4"   => ["winners" => 966,
                  "prize"   => 147],
        "3+2" => ["winners" => 1091,
                  "prize"   => 93],
        "2+2" => ["winners" => 17459,
                  "prize"   => 27],
        "3+1" => ["winners" => 22122,
                  "prize"   => 20],
        "3"   => ["winners" => 47390,
                  "prize"   => 16],
        "1+2" => ["winners" => 99729,
                  "prize"   => 13],
        "2+1" => ["winners" => 366744,
                  "prize"   => 10],
        "2"   => ["winners" => 798965,
                  "prize"   => 5],
    ];
    protected $expectedRegularNumbers = [
        26, 30, 31, 35, 37
    ];
    protected $expectedLuckyNumbers = [
        8, 11
    ];
}