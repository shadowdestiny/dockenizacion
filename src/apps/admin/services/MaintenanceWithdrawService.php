<?php


namespace EuroMillions\admin\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\web\repositories\TransactionRepository;
use EuroMillions\web\vo\dto\WithdrawTransactionDTO;

class MaintenanceWithdrawService
{

    private $entityManager;
    /** @var TransactionRepository */
    protected $transactionRepository;

    public function __construct( EntityManager $entityManager )
    {
        $this->entityManager = $entityManager;
        $this->transactionRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Transaction');
    }

    public function fetchAll( Transaction $transaction = null)
    {
        if($transaction == null ) {
            $transaction = new WinningsWithdrawTransaction();
        }
        $result = $this->transactionRepository->getTransactionsByType($transaction);
        $transactionDTO = [];
        foreach($result as $withdraw) {
            $transactionDTO[] = new WithdrawTransactionDTO($withdraw);
        }
        return $transactionDTO;
    }

    public function changeState( $idTransaction , $state, $transaction = null )
    {
        if ( null == $transaction ) {
            /** @var WinningsWithdrawTransaction $transaction */
            $transaction = $this->transactionRepository->find($idTransaction);
        }
        if( null !== $transaction &&
                $transaction instanceof WinningsWithdrawTransaction) {
            try {
                $transaction->setState($state);
                $transaction->toString();
                $this->entityManager->persist($transaction);
                $this->entityManager->flush();

            } catch ( \Exception $e ) {
                throw new \Exception('An error ocurred while try update state');
            }
        } else {
            throw new \Exception('Sorry, it was a problem. Try again.');
        }
    }



}