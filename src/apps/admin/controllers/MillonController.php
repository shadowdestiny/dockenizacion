<?php


namespace EuroMillions\admin\controllers;


use EuroMillions\admin\services\MillonService;

class MillonController extends AdminControllerBase
{

    /** @var  MillonService $millonService */
    private $millonService;

    public function initialize()
    {
        $this->checkPermissions();
        parent::initialize();
        $this->millonService = $this->domainAdminServiceFactory->getMillonService();
    }



    public function indexAction()
    {

    }

    public function searchAction()
    {
        $date = $this->request->getPost('date');
        $millon = $this->request->getPost('millon');
        $result = $this->millonService->findWinnerMillon(new \DateTime($date), $millon);
        $this->view->pick('millon/index');
        return $this->view->setVars([
            'emailUsers' => $result
        ]);
    }

    private function checkPermissions()
    {
        if (strpos('S', $this->session->get('userAdminAccess'))  !== false) {
            echo 'no entra';
            exit;
        }
    }
}