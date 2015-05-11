<?php
namespace app\controllers;
use Doctrine\ORM\EntityManager;
use Phalcon\Mvc\Controller;

/**
 * Class ControllerBase
 * @package app\controllers
 * @property EntityManager $entityManager
 */
class ControllerBase extends Controller
{
    protected function noRender()
    {
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
    }
}
