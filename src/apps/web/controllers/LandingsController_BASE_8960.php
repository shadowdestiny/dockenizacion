<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 3/10/18
 * Time: 10:52
 */

namespace EuroMillions\web\controllers;


class LandingsController extends PublicSiteControllerBase
{

    public function mainAction()
    {
        $this->view->pick('_elements/landing--blue');
    }

    public function mainorangeAction()
    {
        $this->view->pick('_elements/landing--orange');
    }



}