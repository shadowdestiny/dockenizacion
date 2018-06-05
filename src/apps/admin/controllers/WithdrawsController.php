<?php


namespace EuroMillions\admin\controllers;


use EuroMillions\admin\services\MaintenanceWithdrawService;
use EuroMillions\shared\components\widgets\PaginationWidget;

class WithdrawsController extends AdminControllerBase
{
    /** @var  MaintenanceWithdrawService */
    protected $maintenanceWithdrawService;

    public function initialize()
    {
        $this->checkPermissions();
        parent::initialize();
        $this->maintenanceWithdrawService = $this->domainAdminServiceFactory->getMaintenanceWithdrawService();
    }


    public function indexAction()
    {
        $result = $this->maintenanceWithdrawService->fetchAll();
        list($page, $paginator, $paginator_view) = $this->getPaginate($result);

        return $this->view->setVars([
            'withdraws' => $paginator->getPaginate(),
            'paginator_view' => $paginator_view,
            'page' => $page
        ]);
    }

    public function updateAction()
    {
        $state = $this->request->get('state');
        $idTransaction = $this->request->get('id');
        $paginator = null;
        $paginator_view = null;
        $page = null;
        try {
            $this->maintenanceWithdrawService->changeState($idTransaction,$state);
            $result = $this->maintenanceWithdrawService->fetchAll();
            list($page, $paginator, $paginator_view) = $this->getPaginate($result);

            $this->view->pick('/withdraws/index');

        } catch (\Exception $e ) {

        }
        return $this->view->setVars([
            'withdraws' => $paginator->getPaginate(),
            'paginator_view' => $paginator_view,
            'page' => $page
        ]);

    }

    public function rejectAction()
    {
        $paginator = null;
        $paginator_view = null;
        $page = null;
        $idTransaction = $this->request->getPost('id');
        $message = $this->request->getPost('message' . $idTransaction);
        try {
            $this->maintenanceWithdrawService->giveBackAmountToUserWallet($idTransaction);
            $this->maintenanceWithdrawService->changeState($idTransaction,'rejected', null,$message);
            $listWithdraws = $this->maintenanceWithdrawService->fetchAll();
            list($page, $paginator, $paginator_view) = $this->getPaginate($listWithdraws);
            $this->view->pick('/withdraws/index');
        } catch ( \Exception $e ) {
            throw new \Exception('An error ocurred ' . ' ' . $e->getMessage());
        }
        return $this->view->setVars([
            'withdraws' => $paginator->getPaginate(),
            'paginator_view' => $paginator_view,
            'page' => $page
        ]);

    }

    public function confirmAction()
    {
        $paginator = null;
        $paginator_view = null;
        $page = null;
        $error = null;
        $userID = $this->request->get('userId');
        $idWithDrawRequest = $this->request->get('id');
        try {
            $transactionID = $this->maintenanceWithdrawService->getLastTransactionIDByUser($userID);
            $result = $this->maintenanceWithdrawService->confirmWithDraw($idWithDrawRequest, $transactionID);
            $listWithdraws = $this->maintenanceWithdrawService->fetchAll();
            list($page, $paginator, $paginator_view) = $this->getPaginate($listWithdraws);
            $this->view->pick('/withdraws/index');
        } catch ( \Exception $e ) {
            $error = $e->getMessage();
        }
        return $this->view->setVars([
            'withdraws' => $paginator->getPaginate(),
            'paginator_view' => $paginator_view,
            'page' => $page,
            'error' => $error
        ]);

    }

    public function rejectWithdrawAction() {

    }

    /**
     * @param $result
     * @return array
     */
    private function getPaginate($result)
    {
        $page = (!empty($this->request->getQuery('page'))) ? $this->request->getQuery('page') : 1;
        $paginator = $this->getPaginatorAsArray($result, 15, $page);
        /** @var \Phalcon\Mvc\ViewInterface $paginator_view */
        $paginator_view = (new PaginationWidget($paginator, $this->request->getQuery()))->render();
        return array($page, $paginator, $paginator_view);
    }

    private function checkPermissions()
    {
        if (strpos('S', $this->session->get('userAdminAccess'))  !== false) {
            return $this->response->redirect('/admin/index/notaccess');
        }
    }

}