<?php


namespace tests\unit;


use EuroMillions\components\NullPasswordHasher;
use EuroMillions\entities\User;
use EuroMillions\services\play_strategies\RedisPlayStorageStrategy;
use EuroMillions\vo\Email;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\Password;
use EuroMillions\vo\ServiceActionResult;
use EuroMillions\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use RedisException;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\UnitTestBase;

class RedisPlayStorageStrategyUnitTest extends UnitTestBase
{

    use EuroMillionsResultRelatedTest;

    private $redis_double;

    private $authService_double;

    private $userId;

    const EMLINE_FETCH_KEY = 'PlayStore_EMLINES:';

    public function setUp()
    {
        $this->redis_double = $this->getInterfaceDouble('IRedis');
        $this->authService_double = $this->getServiceDouble('AuthService');
        $this->userId = UserId::create();
        parent::setUp();
    }

    /**
     * method saveAll
     * when calledWithArrayEuroMillionsLine
     * should setEuroMillionsLineArrayInRedisAndResturnServiceActionResultTrue
     */
    public function test_saveAll_calledWithArrayEuroMillionsLine_setEuroMillionsLineArrayInRedisAndResturnServiceActionResultTrue()
    {
        $excepted = new ServiceActionResult(true);
        $sut = $this->getSut();
        $this->redis_double->save(Argument::any(),$this->getEuroMillionsLine())->shouldBeCalled();
        $actual = $sut->saveAll($this->getEuroMillionsLine());
        $this->assertEquals($excepted,$actual);
    }

    /**
     * method findByKey
     * when calledWithKeyInRedisStorage
     * should returnServiceResultActionTrueAndEuroMillionsLineArray
     */
    public function test_findByKey_calledWithKeyInRedisStorage_returnServiceResultActionTrueAndEuroMillionsLineArray()
    {
        $excepted = new ServiceActionResult(true, $this->getEuroMillionsLine());
        $sut = $this->getSut();
        $this->redis_double->get(self::EMLINE_FETCH_KEY)->willReturn($this->getEuroMillionsLine());
        $actual = $sut->findByKey(self::EMLINE_FETCH_KEY);
        $this->assertEquals($excepted,$actual);
    }

    /**
     * method findByKey
     * when keyNotFoundInRedisStore
     * should returnServiceActionResultFalse
     */
    public function test_findByKey_keyNotFoundInRedisStore_returnServiceActionResultFalse()
    {
        $expected = new ServiceActionResult(false,'Key not found');
        $sut = $this->getSut();
        $this->redis_double->get(self::EMLINE_FETCH_KEY)->willReturn(null);
        $actual = $sut->findByKey(self::EMLINE_FETCH_KEY);
        $this->assertEquals($expected,$actual);
    }


    /**
     * method remove
     * when called
     * should removeEuroMillionsLineArrayFromRedisStorage
     */
    public function test_remove_called_removeEuroMillionsLineArrayFromRedisStorage()
    {
        $sut = $this->getSut();
        $this->redis_double->delete(self::EMLINE_FETCH_KEY)->shouldBeCalled();
        $sut->delete(self::EMLINE_FETCH_KEY);
    }

    /**
     * method remove
     * when calledWithEmptyKey
     * should returnServiceActionResultFalseAndNotRemove
     */
    public function test_remove_calledWithEmptyKey_returnServiceActionResultFalseAndNotRemove()
    {
        $expected = new ServiceActionResult(false,'Invalid key');
        $sut = $this->getSut();
        $actual = $sut->delete();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method remove
     * when calledWithValidKey
     * should returnServiceActionResultTrue
     */
    public function test_remove_calledWithValidKey_returnServiceActionResultTrue()
    {
        $expected = new ServiceActionResult(true);
        $sut = $this->getSut();
        $actual  = $sut->delete(self::EMLINE_FETCH_KEY);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method remove
     * when calledWithValidKey
     * should throwExceptionAndReturnServiceActionResultFalse
     */
    public function test_remove_calledWithValidKey_throwExceptionAndReturnServiceActionResultFalse()
    {
        $excepted = new ServiceActionResult(false,'An exception ocurred while delete key');
        $sut = $this->getSut();
        $this->redis_double->delete(self::EMLINE_FETCH_KEY)->willThrow(new RedisException('An exception ocurred while delete key'));
        $actual = $sut->delete(self::EMLINE_FETCH_KEY);
        $this->assertEquals($excepted,$actual);
    }

    /**
     * method findByKey
     * when calledWithValidKey
     * should throwExceptionAndReturnServiceActionResultFalse
     */
    public function test_findByKey_calledWithValidKey_throwExceptionAndReturnServiceActionResultFalse()
    {
        $excepted = new ServiceActionResult(false,'An error ocurred while find key');
        $sut = $this->getSut();
        $this->redis_double->get(self::EMLINE_FETCH_KEY)->willThrow(new RedisException('An error ocurred while find key'));
        $actual = $sut->findByKey(self::EMLINE_FETCH_KEY);
        $this->assertEquals($excepted,$actual);
    }

    /**
     * method saveAll
     * when calledWithValidEuroMillionsArray
     * should throwExceptionAndReturnServiceActionResultFalse
     */
    public function test_saveAll_calledWithValidEuroMillionsArray_throwExceptionAndReturnServiceActionResultFalse()
    {
        $expected = new ServiceActionResult(false,'Unable to save data in storage');
        $sut = $this->getSut();
        $this->redis_double->save(Argument::any(),$this->getEuroMillionsLine())->willThrow(new RedisException('Unable to save data in storage'));
        $actual = $sut->saveAll($this->getEuroMillionsLine());
        $this->assertEquals($expected,$actual);
    }


    protected function getSut()
    {
        $sut = new RedisPlayStorageStrategy($this->redis_double->reveal(), $this->userId);
        return $sut;
    }


    private function getEuroMillionsLine()
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

    /**
     * @param string $currency
     * @return User
     */
    private function getUser($currency = 'EUR')
    {
        $user = new User();
        $user->initialize(
            [
                'id' => new UserId('9098299B-14AC-4124-8DB0-19571EDABE55'),
                'name'     => 'test',
                'surname'  => 'test01',
                'email'    => new Email('raul.mesa@panamedia.net'),
                'password' => new Password('passworD01', new NullPasswordHasher()),
                'validated' => false,
                'balance' => new Money(5000,new Currency($currency)),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }


}