<?php
namespace app\controllers;
use app\services\LanguageService;
use Phalcon\Di;
use stdClass;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
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

