<?php

namespace EuroMillions\shared\tasks;

use EuroMillions\web\tasks\TaskBase;

class ClearTransactionsTask extends TaskBase
{
    private $transactionService;

    public function initialize()
    {
        parent::initialize();
        $this->transactionService = $this->domainServiceFactory->getTransactionService();
    }

    public function clearAction()
    {
        $itemsDeleted = 0;
        $transactionsForDelete = $this->transactionService->getPendingTransactionsEntityId(48*60); //48h in minutes

        foreach ($transactionsForDelete as $item) {
            $itemsDeleted += $this->transactionService->removeTransactionByEntityId($item['id']);
        }

        echo "Pending Transactions Deleted: ".$itemsDeleted."\n";
    }
}
