<?php
namespace app\controllers;
use Doctrine\ORM\EntityManager;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

/**
 * Class ControllerBase
 * @package app\controllers
 * @property EntityManager $entityManager
 */
class ControllerBase extends Controller
{
    protected function noRender()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    }
}
