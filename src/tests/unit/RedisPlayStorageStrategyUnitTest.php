<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\play_strategies\RedisPlayStorageStrategy;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use RedisException;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

class RedisPlayStorageStrategyUnitTest extends UnitTestBase
{

    use EuroMillionsResultRelatedTest;

    private $redis_double;

    private $authService_double;

    private $userId;

    const EMLINE_FETCH_KEY = 'PlayStore_EMLINES:';

    public function setUp()
    {
        $this->redis_double = $this->prophesize('\Redis');
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
        $expected = new ActionResult(true);
        $sut = $this->getSut();
        $playFormToStorage = $this->getPlayFormToStorage();
        $this->redis_double->set(Argument::any(),Argument::any())->shouldBeCalled();
        $actual = $sut->saveAll($playFormToStorage, $this->userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method findByKey
     * when calledWithKeyInRedisStorage
     * should returnServiceResultActionTrueAndEuroMillionsLineArray
     */
    public function test_findByKey_calledWithKeyInRedisStorage_returnServiceResultActionTrueAndEuroMillionsLineArray()
    {
        $expected = new ActionResult(true, $this->getEuroMillionsLines());
        $user = $this->getUser();
        $sut = $this->getSut();
        $this->redis_double->get(self::EMLINE_FETCH_KEY.$user->getId()->id())->willReturn($this->getEuroMillionsLines());
        $actual = $sut->findByKey($user->getId()->id());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method findByKey
     * when keyNotFoundInRedisStore
     * should returnServiceActionResultFalse
     */
    public function test_findByKey_keyNotFoundInRedisStore_returnServiceActionResultFalse()
    {
        $expected = new ActionResult(false,'Key not found');
        $userId = $this->getUser()->getId()->id();
        $sut = $this->getSut();
        $this->redis_double->get(self::EMLINE_FETCH_KEY.$userId)->willReturn(null);
        $actual = $sut->findByKey($userId);
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
        $userId = $this->getUser()->getId()->id();
        $this->redis_double->delete(self::EMLINE_FETCH_KEY.$userId)->shouldBeCalled();
        $sut->delete($userId);
    }

    /**
     * method remove
     * when calledWithEmptyKey
     * should returnServiceActionResultFalseAndNotRemove
     */
    public function test_remove_calledWithEmptyKey_returnServiceActionResultFalseAndNotRemove()
    {
        $expected = new ActionResult(false,'Invalid key');
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
        $expected = new ActionResult(true);
        $userId = $this->getUser()->getId()->id();
        $sut = $this->getSut();
        $actual  = $sut->delete($userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method remove
     * when calledWithValidKey
     * should throwExceptionAndReturnServiceActionResultFalse
     */
    public function test_remove_calledWithValidKey_throwExceptionAndReturnServiceActionResultFalse()
    {
        $expected = new ActionResult(false,'An exception ocurred while delete key');
        $sut = $this->getSut();
        $userId = $this->getUser()->getId()->id();
        $this->redis_double->delete(self::EMLINE_FETCH_KEY.$userId)->willThrow(new RedisException('An exception ocurred while delete key'));
        $actual = $sut->delete($userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method findByKey
     * when calledWithValidKey
     * should throwExceptionAndReturnServiceActionResultFalse
     */
    public function test_findByKey_calledWithValidKey_throwExceptionAndReturnServiceActionResultFalse()
    {
        $expected = new ActionResult(false,'An error ocurred while find key');
        $sut = $this->getSut();
        $userId = $this->getUser()->getId()->id();
        $this->redis_double->get(self::EMLINE_FETCH_KEY.$userId)->willThrow(new RedisException('An error ocurred while find key'));
        $actual = $sut->findByKey($userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method saveAll
     * when calledWithValidEuroMillionsArray
     * should throwExceptionAndReturnServiceActionResultFalse
     */
    public function test_saveAll_calledWithValidEuroMillionsArray_throwExceptionAndReturnServiceActionResultFalse()
    {
        $expected = new ActionResult(false,'Unable to save data in storage');
        $sut = $this->getSut();
        $playFormToStorage = $this->getPlayFormToStorage();
        $this->redis_double->set(Argument::any(),Argument::any())->willThrow(new RedisException('Unable to save data in storage'));
        $actual = $sut->saveAll($playFormToStorage,$this->userId);
        $this->assertEquals($expected,$actual);
    }


    protected function getSut()
    {
        return new RedisPlayStorageStrategy($this->redis_double->reveal());
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
                'wallet' => new Wallet(new Money(5000,new Currency($currency))),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }


}