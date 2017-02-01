<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\TrackingService;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\GeoService;

class TrackingController extends AdminControllerBase
{
    /** @var TrackingService $trackingService */
    private $trackingService;
    /** @var GeoService $geoService */
    private $geoService;
    /** @var CurrencyService $currencyService */
    private $currencyService;

    public function initialize()
    {
        parent::initialize();
        $this->trackingService = $this->domainAdminServiceFactory->getTrackingService();
        $this->geoService = $this->domainAdminServiceFactory->getGeoService();
        $this->currencyService = $this->domainAdminServiceFactory->getCurrencyService();
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

    public function cloneTrackingCodeAction()
    {
        if ($this->request->getPost('name')) {
            if ($this->trackingService->existTrackingCodeName($this->request->getPost('name'))){
                return $this->redirectToTrackingIndex('ERROR - The name is duplicated, choose other name');
            }
            $this->trackingService->cloneTrackingCode([
                'id' => $this->request->getPost(('id')),
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
            ]);
        }
        //ToDo: Clone Actions (Attributs are saved but with exception)
        return $this->redirectToTrackingIndex();
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function editPreferencesAction()
    {
        if ($this->trackingService->getTrackingCodeById($this->request->get('id'))) {
            $countries = $this->geoService->countryList();
            sort($countries);
            //key+1, select element from phalcon need index 0 to set empty value
            $countries = array_combine(range(1, count($countries)), array_values($countries));

            $this->view->pick('tracking/preferences');
            return $this->view->setVars([
                'trackingCode' => $this->trackingService->getTrackingCodeById($this->request->get('id')),
                'attributesChecked' => $this->trackingService->getAttributesTreatedArray($this->request->get('id')),
                'attributes' => $this->trackingService->getAttributesByTrackingCode($this->request->get('id')),
                'actions' => $this->trackingService->getActionsByTrackingCode($this->request->get('id')),
                'countryList' => $countries,
                'currencyList' => $this->currencyService->getActiveCurrenciesCodeAndNames()->returnValues(),
                'lotteries' => $this->trackingService->getLotteries(),
                'allTrackingCodes' => $this->trackingService->getAllTrackingCodesWithUsersCount(),
            ]);
        }

        return $this->redirectToTrackingIndex('ERROR - You don\'t select any tracking code to edit preferences');
    }

    /**
     * @return \Phalcon\Mvc\View
     */
    public function savePreferencesAction()
    {
        if ($this->request->getPost('trackingCodeId')) {
            $this->trackingService->saveTrackingCodePreferences($this->request->getPost());
            $this->trackingService->saveTrackingCodeActions($this->request->getPost());
        } else {
            return $this->redirectToTrackingIndex('ERROR - Preferences cannot be saved');
        }

        return $this->redirectToTrackingIndex('OK - Preferences saved');
    }

    public function launchTrackingCodeAction()
    {
        if ($this->request->get('id')) {
            $this->trackingService->launchTrackingCodeById($this->request->get('id'));
        } else {
            return $this->redirectToTrackingIndex('ERROR - No tracking code received');
        }

        return $this->redirectToTrackingIndex('Tracking Code Launched');
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