<?php


namespace EuroMillions\admin\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\web\repositories\TransactionRepository;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentProvider;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\vo\dto\WithdrawTransactionDTO;
use EuroMillions\web\vo\enum\TransactionType;
use Money\Currency;
use Money\Money;
use Phalcon\Config;
use Phalcon\Di;

class MaintenanceWithdrawService
{

    private $entityManager;
    /** @var TransactionRepository */
    protected $transactionRepository;
    /** @var TransactionService $transactionService */
    private $transactionService;

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
        $transaction = $this->transactionRepository->find($idWithdrawRequest);
        if( $transaction !== null &&
            $transaction instanceof WinningsWithdrawTransaction) {
                try {
                    $transaction->fromString();
                    $amount = new Money((int) $transaction->getAmountWithdrawed(), new Currency('EUR'));
                    //TODO add as dependency, is temporal
                    /** @var WideCardPaymentProvider $paymentProvider */
                    $paymentProvider = Di::getDefault()->get('paymentProviderFactory');
                    $paymentProvider->getConfig()->setEndpoint(Di::getDefault()->get('config')['wirecard']['endpoint_withdraw']);
                    $result = $paymentProvider->withDraw($amount,$idTransaction);
                    $body = json_decode($result->body);
                    if($body->status === 'ok' ) {
                        /** @var DomainServiceFactory $domainFactory */
                        $domainFactory = Di::getDefault()->get('domainServiceFactory');
                        $domainFactory->getTransactionService()->storeTransaction(TransactionType::WINNINGS_WITHDRAW,
                            [
                                'accountBankId' => $transaction->getAccountBankId(),
                                'amountWithdrawed' => $transaction->getAmountWithdrawed(),
                                'state' => 'approved',
                                'walletBefore' => $transaction->getWalletBefore(),
                                'walletAfter' => $transaction->getWalletAfter(),
                                'now' => new \DateTime(),
                                'user' => $transaction->getUser()
                            ]
                        );
                        return new ActionResult(true);
                    }
                    return new ActionResult(false);
                } catch ( \Exception $e ) {
                    throw new \Exception('An error ocurred' . ' ' . $e->getMessage());
                }
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