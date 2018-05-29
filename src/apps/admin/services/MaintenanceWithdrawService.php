<?php


namespace EuroMillions\admin\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\web\repositories\TransactionRepository;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentProvider;
use EuroMillions\web\vo\dto\WithdrawTransactionDTO;
use Money\Currency;
use Money\Money;

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

    public function confirmWithDraw($idWithdrawRequest, $idTransaction)
    {
        /** @var WinningsWithdrawTransaction $transaction */
        $transaction = $this->transactionRepository->find(3);
        if( $transaction !== null &&
            $transaction instanceof WinningsWithdrawTransaction) {
            $transaction->fromString();
            $amount = new Money((int) $transaction->getAmountWithdrawed(), new Currency('EUR'));

        }

    }


    public function getLastTransactionIDByUser($userID)
    {
        try {
            return $this->transactionRepository->getLastTransactionIDAsPurchaseType($userID);
        } catch( Exception $e) {
            throw new \Exception('An error ocurred while get the last id transaction');
        }
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