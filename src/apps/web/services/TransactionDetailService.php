<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\components\transaction\detail\TicketPurchaseDetail;
use EuroMillions\web\interfaces\ITransactionDetailStrategy;

class TransactionDetailService
{

    protected $entityManager;
    protected $transactionRepository;
    protected $currencyConversionService;

    public function __construct( EntityManager $entityManager, CurrencyConversionService $currencyConversionService )
    {
        $this->entityManager = $entityManager;
        $this->transactionRepository = $entityManager->getRepository('EuroMillions\web\entities\Transaction');
        $this->currencyConversionService = $currencyConversionService;
    }



    public function obtainTransactionDetails(Transaction $transaction, ITransactionDetailStrategy $iTransactionDetailStrategy = null)
    {
        $detailTransaction = $iTransactionDetailStrategy;
        list($partOne,$partTwo) = explode('_',$transaction->getEntityType());
        $class = 'EuroMillions\web\components\transaction\detail\\'.ucfirst($partOne).ucfirst($partTwo).'Detail';
        if($detailTransaction == null ) {
            /** @var ITransactionDetailStrategy $detailTransaction */
            $detailTransaction = new $class($this->entityManager,$transaction);
        }
        return $detailTransaction->obtainDataForDetails();
    }



}