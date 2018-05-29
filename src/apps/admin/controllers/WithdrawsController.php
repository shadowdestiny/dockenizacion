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

    public function confirmAction()
    {
        $userID = $this->request->getPost('userId');
        $idWithDrawRequest = $this->request->getPost('id');
        $userID=1;
        $idWithDrawRequest=3;
        try {
            $transactionID = $this->maintenanceWithdrawService->getLastTransactionIDByUser($userID);
            $result = $this->maintenanceWithdrawService->confirmWithDraw($userID, $idWithDrawRequest);
        } catch ( \Exception $e ) {
            throw new \Exception('An error ocurred ' . ' ' . $e->getMessage());
        }
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