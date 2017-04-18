<?php

namespace EuroMillions\admin\controllers;

use EuroMillions\admin\services\TranslationService;

class TranslationController extends AdminControllerBase{

    /** @var TranslationService $translationService */
    private $translationService;

    public function initialize()
    {
        parent::initialize();
        $this->translationService = $this->domainAdminServiceFactory->getTranslationService();
    }

    public function indexAction(){
        $this->view->setVars([
            'needLanguagesMenu' => true,
        ]);
    }

    public function categoriesAction(){
        $this->view->setVars([
            'needLanguagesMenu' => true,
            'categoriesList' => $this->translationService->getAllTranslationCategories(),
        ]);
    }

}