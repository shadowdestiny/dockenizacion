<?php


namespace tests\unit\admin;


use EuroMillions\shareconfig\Namespaces;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\entities\User;
use EuroMillions\admin\vo\ActionResult;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use tests\base\PhalconDiRelatedTest;
use tests\base\UnitTestBase;

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

    private function getSut()
    {
        return $sut = $this->getDomainAdminServiceFactory()->getMaintenanceService();
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
                'id' => new UserId('9098299B-14AC-4124-8DB0-19571EDABE55'),
                'name'     => 'test',
                'surname'  => 'test01',
                'email'    => new Email('raul.mesa@panamedia.net'),
                'password' => new Password('passworD01', new NullPasswordHasher()),
                'validated' => false,
                'balance' => new Money(5000,new Currency($currency)),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ],
            [
                'id' => new UserId('9098299B-14AC-4124-8DB0-19571EDABE556'),
                'name'     => 'test',
                'surname'  => 'test02',
                'email'    => new Email('raul.mesa2@panamedia.net'),
                'password' => new Password('passworD01', new NullPasswordHasher()),
                'validated' => false,
                'balance' => new Money(5000,new Currency($currency)),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0e'
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
        $actual = $sut->listAllUsers();
        return $actual;
    }

}