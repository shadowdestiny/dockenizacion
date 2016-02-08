<?php


namespace tests\unit;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\services\play_strategies\RedisOrderStorageStrategy;
use EuroMillions\web\vo\UserId;
use tests\base\UnitTestBase;

class RedisOrderStorageStrategyUnitTest extends UnitTestBase
{

    private $redis_double;

    private $authService_double;

    private $userId;

    const EMLINE_FETCH_KEY = 'PlayStore_EMORDER:';

    public function setUp()
    {
        $this->redis_double = $this->getInterfaceWebDouble('IRedis');
        $this->authService_double = $this->getServiceDouble('AuthService');
        $this->userId = UserId::create();
        parent::setUp();
    }

    /**
     * method save
     * when calledPassingProperData
     * should persistOrderOnStorage
     */
    public function test_save_calledPassingProperData_persistOrderOnStorage()
    {

        $this->markTestIncomplete('Order redis');
        $expected = new ActionResult(true);
        $json = '';
        $sut = $this->getSut();
        $actual = $sut->save($json,$this->userId);
        $this->assertEquals($expected,$actual);
    }


    private function getOrder()
    {
//        $string_json = '{"drawDays":"1","startDrawDate":"05 Feb 2016","lastDrawDate":"2016-02-05 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[3,8,11,16,44],"lucky":[3,5]},{"regular":[6,17,37,38,48],"lucky":[1,5]}]},"numbers":null,"threshold":null,"num_weeks":0}';
//        $form_decode = json_decode($string_json);
//        $bets = [];
//        foreach($form_decode->euroMillionsLines->bets as $bet) {
//            $bets[] = $bet;
//        }
//        $user = UserMother::aUserWith500Eur()->build();
//        $playConfig = new PlayConfig();
//        $playConfig->formToEntity($user,$string_json,$bets);
//
//        $single_bet_price = new Money(2500, new Currency('EUR'));
//        $fee = new Money(35, new Currency('EUR'));
//        $fee_limit = new Money(12000, new Currency('EUR'));
//        $sut = new Order($playConfig, $single_bet_price, $fee, $fee_limit);

    }

    protected function getSut()
    {
        return new RedisOrderStorageStrategy($this->redis_double->reveal());
    }


}