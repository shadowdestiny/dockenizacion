<?php


namespace EuroMillions\tests\unit\admin;


use EuroMillions\admin\services\MaintenanceUserService;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\entities\User;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\base\PhalconDiRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

class MaintenanceUserServiceUnitTest extends UnitTestBase

{
    use PhalconDiRelatedTest;

    private $userRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
        ];
    }

    public function setUp()
    {
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        parent::setUp();
    }


    /**
     * method listAllUsers
     * when called
     * should returnActionResultTrueWithArrayOfUsers
     */
    public function test_listAllUsers_called_returnActionResultTrueWithArrayOfUsers()
    {
        $expected = new ActionResult(true,$this->getUsers());
        $actual = $this->expectedListAllUsers($this->getUsers());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method listAllUsers
     * when calledWithoutUsers
     * should returnActionResultFalse
     */
    public function test_listAllUsers_calledWithoutUsers_returnActionResultFalse()
    {
        $expected = new ActionResult(false);
        $actual = $this->expectedListAllUsers();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method updateUserBalance
     * when calledWithValidUserId
     * should updateBalanceFromUser
     */
    public function test_updateUserBalance_calledWithValidUserId_updateBalanceFromUser()
    {
        $user = $this->getUsers();
        $amount = new Money(5000, new Currency('EUR'));
        $this->userRepository_double->find($user->getId())->willReturn($user);
        $this->userRepository_double->add($user);
        $this->iDontCareAboutFlush();
        $sut = $this->getSut();
        $actual = $sut->updateBalance('9098299B-14AC-4124-8DB0-19571EDABE55', $amount);
        $this->assertTrue($actual->success());
    }

    /**
     * method updateUserBalance
     * when calledWithInvalidUserId
     * should returnActionResultFalse
     */
    public function test_updateUserBalance_calledWithInvalidUserId_returnActionResultFalse()
    {
        $user = $this->getUsers();
        $amount = new Money(5000, new Currency('EUR'));
        $this->userRepository_double->find($user->getId())->willReturn(null);
        $this->userRepository_double->add($user);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush()->shouldNotBeCalled();
        $this->stubEntityManager($entityManager_stub);
        $sut = $this->getSut();
        $actual = $sut->updateBalance('9098299B-14AC-4124-8DB0-19571EDABE55', $amount);
        $this->assertFalse($actual->success());
    }

    private function getSut()
    {
        return new MaintenanceUserService($this->getEntityManagerRevealed());
    }

    /**
     * @param string $currency
     * @return User
     */
    private function getUsers($currency = 'EUR')
    {
        $user = new User();
        $user->initialize(
            [
                'id' => '9098299B-14AC-4124-8DB0-19571EDABE55',
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

    /**
     * @return ActionResult
     */
    private function expectedListAllUsers($listUsers = null)
    {
        $this->userRepository_double->findAll()->willReturn($listUsers);
        $sut = $this->getSut();
        return $sut->listAllUsers();
    }

}