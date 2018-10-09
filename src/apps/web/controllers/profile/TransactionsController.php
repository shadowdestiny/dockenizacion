<?php


namespace EuroMillions\web\controllers\profile;


use EuroMillions\shared\components\widgets\PaginationWidgetDoctrine;
use EuroMillions\web\controllers\AccountController;
use Money\Currency;
use Money\Money;

class TransactionsController extends AccountController
{


    /**
     * @return \Phalcon\Mvc\View
     */
    public function transactionAction()
    {
        $user = $this->authService->getLoggedUser();

        $page = (!empty($this->request->get('page'))) ? $this->request->get('page') : 1;

        $transactionDtoCollection = $this->transactionService->getTransactionsDTOByUser( $user, $page );

        if(!empty($transactionDtoCollection))
        {
            $totalElements=$transactionDtoCollection['totalElements'];
            $transactionDtoCollection=$transactionDtoCollection['transactionDtoCollection'];
        }
        else{
            $totalElements=0;
        }

        /** @var \Phalcon\Mvc\ViewInterface $paginator_view */
        $paginator_view = (new PaginationWidgetDoctrine($page ,$totalElements, $this->request->getQuery()))->render();
        $this->view->pick('account/transaction');
        $this->tag->prependTitle('Transaction History');

        return $this->view->setVars([
            'transactionCollection' => $transactionDtoCollection,
            'page' => $page,
            'paginator_view' => $paginator_view,
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol']
        ]);
    }


}