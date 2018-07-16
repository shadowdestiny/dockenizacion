<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\BigWinTransaction;
use EuroMillions\web\entities\DepositTransaction;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\SubscriptionPurchaseTransaction;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\WinningsReceivedTransaction;
use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\vo\dto\TransactionDTO;
use Money\Currency;
use Money\Money;


class TransactionService
{

    protected $entityManager;
    protected $transactionRepository;
    protected $currencyConversionService;

    public function __construct(EntityManager $entityManager, CurrencyConversionService $currencyConversionService)
    {
        $this->entityManager = $entityManager;
        $this->transactionRepository = $entityManager->getRepository('EuroMillions\web\entities\Transaction');
        $this->currencyConversionService = $currencyConversionService;
    }


    public function storeTransaction($transactionType,
                                     array $data)
    {
        if ($data['now'] == null) {
            $data['now'] = new \DateTime();
        }
        list($partOne, $partTwo) = explode('_', $transactionType);
        $class = 'EuroMillions\web\components\transaction\\' . ucfirst($partOne) . ucfirst($partTwo) . 'Generator';
        try {
            /** @var Transaction $entity */
            $entity = $class::build($data);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return new ActionResult(false);
        }
        return new ActionResult(true, $entity);
    }

    public function getTransactionsDTOByUser(User $user)
    {
        $result = $this->transactionRepository->findBy(['user' => $user->getId()], ['id' => 'DESC']);
        if (null != $result) {
            $transactionDtoCollection = [];
            /** @var LotteryRepository $lotteryRepository */
            $lotteryRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS . 'Lottery');
            /** @var Transaction $transaction */
            foreach ($result as $transaction) {
                if ($transaction instanceof BigWinTransaction) continue;
                $transactionDTO = new TransactionDTO($transaction);
                $transactionDTO->lotteryId = $this->getLotteryName($lotteryRepository,$transaction);
                $movement = $this->currencyConversionService->convert($transactionDTO->movement, new Currency('EUR'));
                $balance = $this->currencyConversionService->convert($transactionDTO->balance, new Currency('EUR'));
                $winnings = $this->currencyConversionService->convert($transactionDTO->winnings, new Currency('EUR'));
                $pendingBalance = $this->currencyConversionService->convert($transactionDTO->pendingBalance, new Currency('EUR'));
                $pendingBalanceMovement = $this->currencyConversionService->convert($transactionDTO->pendingBalanceMovement, new Currency('EUR'));
                $ticketPrice = $this->currencyConversionService->convert($transactionDTO->ticketPrice, new Currency('EUR'));
                $transactionDTO->movement = $transactionDTO->formatMovement($this->currencyConversionService->toString($movement, $user->getLocale()));
                $transactionDTO->balance = $this->currencyConversionService->toString($balance, $user->getLocale());
                $transactionDTO->winnings = $this->currencyConversionService->toString($winnings, $user->getLocale());
                $transactionDTO->pendingBalance = $this->currencyConversionService->toString($pendingBalance, $user->getLocale());
                $transactionDTO->pendingBalanceMovement = $this->currencyConversionService->toString($pendingBalanceMovement, $user->getLocale());
                $transactionDTO->ticketPrice = $this->currencyConversionService->toString($ticketPrice, $user->getLocale());
                $transactionDtoCollection[] = $transactionDTO;
            }
            return $transactionDtoCollection;
        }
        return [];
    }

    //Shit function
    private function getLotteryName(LotteryRepository $lotteryRepository,Transaction $transaction)
    {

        try {
            /** @var Lottery$lottery */
            $lottery = null;
            if($transaction instanceof WinningsWithdrawTransaction or $transaction instanceof DepositTransaction) return "";
            $transaction->fromString();
            $lottery = $lotteryRepository->findBy(["id" => ($transaction->getLotteryId()) ? $transaction->getLotteryId() : 1]);
            return $lottery[0]->getName();
        } catch (\Exception $e)
        {
            return "";
        }

    }

    public function getTransaction($id)
    {
        /** @var Transaction $transactionEntity */
        $transactionEntity = $this->transactionRepository->findBy(["id" => $id]);
        if ($transactionEntity != null) {
            return $transactionEntity;
        } else {
            return null;
        }
    }

    public function getSubscriptionByLotteryAndUserId($lotteryName, $userId)
    {
        try {
            /** @var LotteryRepository $lotteryRepository */
            $lotteryRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS . 'Lottery');
            $lottery = $lotteryRepository->getLotteryByName($lotteryName);
            return new Money((int) $this->transactionRepository->getSubscriptionBalanceByLottery($lottery->getId(), $userId),
                new Currency('EUR')
            );
        } catch(\Exception $e)
        {
            return $e->getMessage();
        }

    }

    public function getUniqueTransactionId()
    {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function getLastId()
    {
        return $this->transactionRepository->getNextId();
    }

    public function getWinningTransactions()
    {
        $date = new \DateTime();
        if ($this->transactionRepository->getAwardedByDate($date->format('Y-m-d')))
        {
            return true;
        } else {
            return false;
        }
    }


}