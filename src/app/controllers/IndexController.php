<?php
namespace EuroMillions\controllers;
use Phalcon\Di;

class IndexController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $time_till_next_draw = $this->lotteriesDataService->getTimeToNextDraw('EuroMillions');
        $this->view->setVar('euromillions_results', $this->lotteriesDataService->getLastResult('EuroMillions'));
        $this->view->setVar('days_till_next_draw', $time_till_next_draw->d);
        $this->view->setVar('hours_till_next_draw', $time_till_next_draw->h);
        $this->view->setVar('minutes_till_next_draw', $time_till_next_draw->i);
    }

    public function notfoundAction($exception = null)
    {
        $this->noRender();
        echo "ERROR 404";
        var_dump($exception);
    }
}

