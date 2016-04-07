<?php


namespace EuroMillions\tests\integration;


use EuroMillions\shared\vo\Wallet;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\BetTransaction;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\repositories\TransactionRepository;


class TransactionIntegrationTest extends DatabaseIntegrationTestBase
{

    /** @var  TransactionRepository */
    protected $transactionRepository;
    
    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'users',
            'transactions',
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->transactionRepository = $this->entityManager->getRepository($this->getEntitiesToArgument('Transaction'));
    }


    /**
     * method test
     * when called
     * should creat
     */
    public function test_test_called_creat()
    {
        $transactionString = '5#4';
        $date = new \DateTime();
        $user = UserMother::aJustRegisteredUser()->build();

        $transactionType = new TicketPurchaseTransaction();
        $transactionType->setDate($date);
        $transactionType->setData($transactionString);
        $transactionType->setEntityType('ticket_purchase');
        $transactionType->setUser($user);
        $wallet_before = new Wallet();
        $wallet_before->create(0,0);
        $transactionType->setWalletBefore($wallet_before);
        $this->entityManager->persist($user);
        $this->entityManager->persist($transactionType);
        $this->transactionRepository->add($transactionType);
        $this->entityManager->flush();
        $actual = $this->entityManager
            ->createQuery(
                'SELECT t'
                . ' FROM \EuroMillions\web\entities\Transaction t')
            ->getResult();
        $this->assertCount(2,$actual);
    }


    /**
     * method getToString
     * when called
     * should returnEntityTypeInfo
     */
    public function test_getToString_called_returnEntityTypeInfo()
    {
        $actual = $this->entityManager
            ->createQuery(
                'SELECT t'
                . ' FROM \EuroMillions\web\entities\Transaction t')
            ->getResult();

        $this->assertInstanceOf('EuroMillions\web\entities\TicketPurchaseTransaction',$actual[0]);

    }
    
    
}