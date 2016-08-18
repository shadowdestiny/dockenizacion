<?php


namespace EuroMillions\web\controllers\ajax;


class TransactionDetailController extends AjaxControllerBase
{


    public function obtainAction($id)
    {
        $transactionService = $this->domainServiceFactory->getTransactionService();
        $transactionDetailService = $this->domainServiceFactory->getTransactionDetailService();
        $transactionEntity = $transactionService->obtainTransaction($id);
        if(null != $transactionEntity) {
            $transactionDetailService->obtainTransactionDetails($transactionEntity);
        }
    }


}