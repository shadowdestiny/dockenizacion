<?php
namespace app\controllers;
use Phalcon\Di;

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
        $this->response->redirect($uri);
    }

    public function testAction()
    {
        $this->noRender();
        $di = $this->getDI();
        $entity_manager = $di->get('entityManager');
        var_dump($entity_manager);
    }

}

