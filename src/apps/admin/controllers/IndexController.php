<?php

namespace EuroMillions\admin\controllers;


use Phalcon\Mvc\View;

class IndexController extends AdminControllerBase{

    public function indexAction(){

    }
    public function businessAction(){}
    public function systemAction(){}
    public function translationAction()
    {
        $this->view->pick('index/languages');
    }
    public function languagesAction(){}
    public function detailAction(){}
    public function newsAction(){}
    public function adminAction(){}
    public function jackpotAction(){}
    public function accountAction(){}

}