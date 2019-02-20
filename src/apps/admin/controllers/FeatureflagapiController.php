<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\FeatureFlagApiService;

class FeatureflagapiController extends AdminControllerBase
{
    /** @var FeatureFlagApiService $featureFlagApiService */
    private $featureFlagApiService;

    public function initialize()
    {
        $this->checkPermissions();
        parent::initialize();
        $this->featureFlagApiService = $this->domainServiceFactory->getFeatureFlagApiService();
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function indexAction()
    {
        $message = $this->cookies->get('message');
        $this->cookies->set('message', '');
        $this->view->pick('featureFlagApi/index');
        return $this->view->setVars([
            'features' => $this->featureFlagApiService->getItems(),
            'errorMessage' => $message,
        ]);
    }

    /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function editFeatureAction()
    {
        if ($this->request->getPost('name')) {
            $this->featureFlagApiService->updateItem([
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'status' => $this->request->getPost('status'),
            ]);
        }
        return $this->redirectToIndex();
    }

    /**
     * @param null $message
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    private function redirectToIndex($message = null)
    {
        $this->cookies->set('message', $message);
        return $this->response->redirect('/admin/featureFlagApi/index');
    }

    private function checkPermissions()
    {
        if (strpos('S', $this->session->get('userAdminAccess'))  !== false) {
            return $this->response->redirect('/admin/index/notaccess');
        }
    }
}