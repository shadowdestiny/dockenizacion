<?php


namespace EuroMillions\web\controllers\ajax;


use EuroMillions\web\entities\Transaction;

class TransactionDetailController extends AjaxControllerBase
{


    public function obtainAction($id)
    {
        $transactionService = $this->domainServiceFactory->getTransactionService();
        $transactionDetailService = $this->domainServiceFactory->getTransactionDetailService();
        /** @var Transaction $transactionEntity */
        $transactionEntity = $transactionService->obtainTransaction($id)[0];
        if(null != $transactionEntity) {
            $result = $transactionDetailService->obtainTransactionDetails($transactionEntity);
            echo json_encode($result);
        }
    }
}