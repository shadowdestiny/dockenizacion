<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\TrackingService;

class TrackingController extends AdminControllerBase
{
    /** @var TrackingService $trackingService */
    private $trackingService;

    public function initialize()
    {
        parent::initialize();
        $this->trackingService = $this->domainAdminServiceFactory->getTrackingService();
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function indexAction()
    {
        return $this->redirectToTrackingIndex();
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function createTrackingCodeAction()
    {
        if ($this->request->getPost('name')) {
            if ($this->trackingService->existTrackingCodeName($this->request->getPost('name'))){
                return $this->redirectToTrackingIndex('ERROR - The name is duplicated, choose other name');
            }
            $this->trackingService->createTrackingCode([
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                ]);
        }
        return $this->redirectToTrackingIndex();
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function editTrackingCodeAction()
    {
        if ($this->request->getPost('id')) {
            $this->trackingService->editTrackingCode([
                'id' => $this->request->getPost('id'),
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
            ]);
        }
        return $this->redirectToTrackingIndex();
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function deleteTrackingCodeAction()
    {
        if ($this->request->get('id')) {
            $this->trackingService->deleteTrackingCode($this->request->get('id'));
        }
        return $this->redirectToTrackingIndex();
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function downloadUsersAction()
    {
        if ($this->request->get('id')) {
            $usersList = $this->trackingService->getUsersListByTrackingCode($this->request->get('id'));
            if (count($usersList) > 0) {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $fp = fopen('php://output', 'w');
                foreach ($usersList as $user) {
                    fputcsv($fp, [$user['user_id']]);
                }
                fclose($fp);
            } else {
                return $this->redirectToTrackingIndex('ERROR - No users for this Tracking Code');
            }
        }
    }

    /**
     * @param null $errorMessage
     * @return \Phalcon\Mvc\View
     */
    private function redirectToTrackingIndex($errorMessage = null)
    {
        $this->view->pick('tracking/index');
        return $this->view->setVars([
            'trackingCodes' => $this->trackingService->getAllTrackingCodesWithUsersCount(),
            'errorMessage' => $errorMessage,
        ]);
    }
}