<?php
namespace app\controllers;
use app\entities\Language;
use app\services\LanguageService;
use Doctrine\ORM\EntityManager;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

/**
 * Class ControllerBase
 * @package app\controllers
 * @property EntityManager $entityManager
 * @property LanguageService $language
 */
class ControllerBase extends Controller
{
    protected function noRender()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    }

    public function beforeExecuteRoute()
    {
        $this->setActiveLanguages();
    }

    protected function setActiveLanguages()
    {
        $languages = $this->language->activeLanguages();
        $view_params = [];
        /** @var Language $language */
        foreach ($languages as $language) {
            $view_params[] = $language->toValueObject();
        }
        $this->view->setVars(['languages' => $view_params]);
    }


}
