<?php


namespace EuroMillions\web\controllers\profile;

use EuroMillions\shared\components\widgets\PaginationWidget;
use EuroMillions\web\controllers\AccountController;
use antonienko\MoneyFormatter\MoneyFormatter;
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
        $transactionDtoCollection = $this->transactionService->getTransactionsDTOByUser( $user );

        $page = (!empty($this->request->get('page'))) ? $this->request->get('page') : 1;
        $paginator = $this->getPaginatorAsArray($transactionDtoCollection,10,$page);
        /** @var \Phalcon\Mvc\ViewInterface $paginator_view */
        $paginator_view = (new PaginationWidget($paginator, $this->request->getQuery()))->render();
        $this->view->pick('account/transaction');
        $this->tag->prependTitle('Transaction History');
        $money_formatter = new MoneyFormatter();

        return $this->view->setVars([
            'transactionCollection' => $paginator->getPaginate()->items,
            'page' => $page,
            'paginator_view' => $paginator_view,
            'symbol' => $money_formatter->getSymbol($user->getLocale(), new Money(0, new Currency('EUR')))
        ]);
    }


}