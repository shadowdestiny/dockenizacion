<?php


namespace EuroMillions\admin\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\web\repositories\TransactionRepository;
use EuroMillions\web\entities\Transaction;

class ReportsService
{

    private $entityManager;
    /** @var TransactionRepository $transactionRepository */
    private $transactionRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->transactionRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Transaction');
    }
}
