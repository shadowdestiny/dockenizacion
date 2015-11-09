<?php


namespace tests\integration;


use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\entities\CreditCardPaymentMethod;
use EuroMillions\web\entities\User;
use EuroMillions\web\repositories\PaymentMethodRepository;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\ExpiryDate;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use tests\base\DatabaseIntegrationTestBase;

class PaymentMethodRepositoryIntegrationTest extends DatabaseIntegrationTestBase
{
    /** @var PaymentMethodRepository */
    protected $sut;

    protected function getFixtures()
    {
        return [
            'users',
            'payment_methods'
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->sut = $this->entityManager->getRepository($this->getEntitiesToArgument('PaymentMethod'));
    }

    /**
     * method add
     * when calledWithValidPaymentMethod
     * should storeCorrectlyInTheDatabase
     */
    public function test_add_calledWithValidPaymentMethod_storeCorrectlyInTheDatabase()
    {
        $user = $this->entityManager->find($this->getEntitiesToArgument('User'), '9098299B-14AC-4124-8DB0-19571EDABE55');
        $creditCard = $this->getCreditCard();
        list($paymentMethod, $actual) = $this->exerciseAdd($user, $creditCard);
        $this->assertEquals($paymentMethod, $actual);
    }

    /**
     * method getPaymentMethodsByUser
     * when calledWithValidUser
     * should returnAnInstanceCreditCardPaymentMethod
     */
    public function test_getPaymentMethodsByUser_calledWithValidUser_returnAnInstanceCreditCardPaymentMethod()
    {
        $user = $this->entityManager->find($this->getEntitiesToArgument('User'), '9098299B-14AC-4124-8DB0-19571EDABE55');
        $expected = $this->getEntitiesToArgument('CreditCardPaymentMethod');
        $creditCard = $this->getCreditCard();
        $this->exerciseAdd($user, $creditCard);
        $actual = $this->sut->getPaymentMethodsByUser($user);
        $this->assertInstanceOf($expected,$actual[0]);
    }

    private function exerciseAdd($user,$creditCard)
    {
        $paymentMethod = new CreditCardPaymentMethod($creditCard);
        $paymentMethod->setUser($user);
        $this->sut->add($paymentMethod);
        $this->entityManager->flush();
        $actual = $this->entityManager
            ->createQuery(
                'SELECT p'
                . ' FROM \EuroMillions\web\entities\PaymentMethod p'
                . ' WHERE p.user = :user_id ')
            ->setMaxResults(1)
            ->setParameters(['user_id' => $user->getId() ])
            ->getResult()[0];
        return array($paymentMethod,$actual);
    }

    /**
     * @return CreditCard
     */
    private function getCreditCard()
    {
        return $creditCard = new CreditCard(new CardHolderName('Raul Mesa Ros'),
            new CardNumber('5500005555555559'),
            new ExpiryDate('10/19'),
            new CVV('123')
        );
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