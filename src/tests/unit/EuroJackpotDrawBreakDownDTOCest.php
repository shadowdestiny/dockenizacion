<?php

use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;
use EuroMillions\eurojackpot\vo\dto\EuroJackpotDrawBreakDownDTO;

class EuroJackpotDrawBreakDownDTOCest
{
    protected $breakDownDTO;

    public function _before(\UnitTester $I)
    {
        $draw = EuroMillionsDrawMother::anEuroJackpotDrawWithJackpotAndBreakDown()->build();
        $this->breakDownDTO = (new EuroJackpotDrawBreakDownDTO($draw->getBreakDown()))->toArray();
    }

    /**
     * method euroJackpotDrawBreakDownDTO
     * when hasBreakDown
     * should getTheCorrectResult
     * @param UnitTester $I
     * @group eurojackpot-breakdown-dto
     * @dataProvider getData
     */
    public function test_euroJackpotDrawBreakDownDTO_hasBreakDown_getTheCorrectResult(UnitTester $I, \Codeception\Example $data)
    {
        $I->assertEquals($data['name'], $this->breakDownDTO[$data['category']]['name']);
        $I->assertEquals($data['prize'], $this->breakDownDTO[$data['category']]['lottery_prize']);
        $I->assertEquals($data['winners'], $this->breakDownDTO[$data['category']]['winners']);
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return [
            ['category' => 'category_one',    'name' => 'match-5-2',  'prize' => 162613400,   'winners' => 0],
            ['category' => 'category_two',    'name' => 'match-5-1',  'prize' => 62613400,    'winners' => 3],
            ['category' => 'category_three',  'name' => 'match-5',    'prize' => 16574130,    'winners' => 4],
            ['category' => 'category_four',   'name' => 'match-4-2',  'prize' => 374550,      'winners' => 59],
            ['category' => 'category_five',   'name' => 'match-4-1',  'prize' => 28780,       'winners' => 691],
            ['category' => 'category_six',    'name' => 'match-4',    'prize' => 13150,       'winners' => 1176],
            ['category' => 'category_seven',  'name' => 'match-3-2',  'prize' => 4560,        'winners' => 2907],
            ['category' => 'category_eight',  'name' => 'match-2-2',  'prize' => 1710,        'winners' => 43366],
            ['category' => 'category_nine',   'name' => 'match-3-1',  'prize' => 1710,        'winners' => 35325],
            ['category' => 'category_ten',    'name' => 'match-3',    'prize' => 1660,        'winners' => 57090],
            ['category' => 'category_eleven', 'name' => 'match-1-2',  'prize' => 780,         'winners' => 224171],
            ['category' => 'category_twelve', 'name' => 'match-2-1',  'prize' => 780,         'winners' => 535178],
        ];
    }
}
