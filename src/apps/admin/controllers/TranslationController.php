<?php

namespace EuroMillions\admin\controllers;

class TranslationController extends AdminControllerBase{

    public function indexAction(){
        $this->view->setVars([
            'needLanguagesMenu' => true,
        ]);
    }

}