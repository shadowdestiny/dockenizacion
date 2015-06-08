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
        $jackpot = $lds->getNextJackpot();
        $this->view->setVar('jackpot_value', $jackpot);
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

