<?php
namespace app\controllers;
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

    public function testAction()
    {
        $this->noRender();
        $object = new stdClass();
        $object->property1 = 983;
        $object->property2="skldfsj";
        var_dump(json_encode($object));
    }

}

