<?php
namespace EuroMillions\controllers;
use EuroMillions\services\LanguageService;
use EuroMillions\services\LotteriesDataService;
use Phalcon\Di;
use stdClass;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $lds = new LotteriesDataService();
        $time_till_next_draw = $lds->getTimeToNextDraw('EuroMillions');
        $this->view->setVar('euromillions_results', $lds->getLastResult('EuroMillions'));
        $this->view->setVar('jackpot_value', $lds->getNextJackpot('EuroMillions'));
        $this->view->setVar('currency_symbol_first', true); //EMTD
        $this->view->setVar('days_till_next_draw', $time_till_next_draw->d);
        $this->view->setVar('hours_till_next_draw', $time_till_next_draw->h);
        $this->view->setVar('minutes_till_next_draw', $time_till_next_draw->i);


    }

    public function fallBackToZendAction()
    {
        $this->noRender();
        $uri = Di::getDefault()->get('request')->getURI();
        if (strpos($uri, '?') !== false) {
            $uri .= '&zfb=1';
        } else {
            $uri .= '?zfb=1';
        }
        $this->response->redirect($uri, true, 307);
    }
}

