<?php


namespace tests\unit;


use EuroMillions\components\NullPasswordHasher;
use EuroMillions\config\Namespaces;
use EuroMillions\entities\User;
use EuroMillions\vo\Email;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\Password;
use EuroMillions\vo\ServiceActionResult;
use EuroMillions\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\UnitTestBase;

class PlayServiceUnitTest extends UnitTestBase
{

    use EuroMillionsResultRelatedTest;

    private $playConfigRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'PlayConfig' => $this->playConfigRepository_double
        ];
    }

    public function setUp()
    {
        $this->playConfigRepository_double = $this->getRepositoryDouble('PlayConfigRepository');
        parent::setUp();
    }

    /**
     * method play
     * when called
     * should returnServiceActionResultTrue
     */
    public function test_play_called_returnServiceActionResultTrue()
    {
        $expected = new ServiceActionResult(true);
        $user = $this->getUser();
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsResult = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
                                                    $this->getLuckyNumbers($lucky_numbers));

        $sut = $this->getSut();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::any())->shouldNotBeCalled();
        $this->stubEntityManager($entityManager_stub);
        $actual = $sut->play($user,$euroMillionsResult);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method play
     * when calledWithUserWithoutBalance
     * should returnServiceActionResultFalse
     */
    public function test_play_calledWithUserWithoutBalance_returnServiceActionResultFalse()
    {
        $expected = new ServiceActionResult(false);
        $user = $this->getUser();
        $user->setBalance(new Money(0,new Currency('EUR')));
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsResult = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $sut = $this->getSut();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::any())->shouldNotBeCalled();
        $this->stubEntityManager($entityManager_stub);
        $actual = $sut->play($user,$euroMillionsResult);
        $this->assertEquals($expected,$actual);
    }

    private function getSut(){
        $sut = $this->getDomainServiceFactory()->getPlayService();
        return $sut;
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