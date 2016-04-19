<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\dto\TransactionDTO;
use Money\Currency;


class TransactionService
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


    public function storeTransaction( $transactionType ,
                                      array $data)
    {
        if( null == $data['now'] ) {
            $data['now'] = new \DateTime();
        }
        list($partOne,$partTwo) = explode('_',$transactionType);
        $class = 'EuroMillions\web\components\transaction\\'.ucfirst($partOne).ucfirst($partTwo).'Generator';
        try {
            /** @var Transaction $entity */
            $entity = $class::build($data);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch ( \Exception $e ) {
            return new ActionResult(false);
        }
        return new ActionResult(true, $entity);
    }

    public function getTransactionsDTOByUser( User $user )
    {
        $result = $this->transactionRepository->findBy(['user' => $user->getId() ]);
        if( null != $result ) {
            $transactionDtoCollection = [];
            /** @var Transaction $transaction */
            foreach($result as $transaction) {
                $transactionDTO = new TransactionDTO($transaction);
                $movement = $this->currencyConversionService->convert($transactionDTO->movement, new Currency('EUR'));
                $balance = $this->currencyConversionService->convert($transactionDTO->balance, new Currency('EUR'));
                $winnings = $this->currencyConversionService->convert($transactionDTO->winnings, new Currency('EUR'));
                $transactionDTO->movement = $this->currencyConversionService->toString($movement, $user->getLocale());
                $transactionDTO->balance = $this->currencyConversionService->toString($balance, $user->getLocale());
                $transactionDTO->winnings = $this->currencyConversionService->toString($winnings, $user->getLocale());
                $transactionDtoCollection[] = $transactionDTO;
            }
            return $transactionDtoCollection;
        }
        return [];
    }
}