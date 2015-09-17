<?php
namespace EuroMillions\controllers;

class PlayController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        //EMTD remove this value :)
        return $this->view->setVars([
            'jackpot_value'  => '10000000000',
        ]);

    }
}