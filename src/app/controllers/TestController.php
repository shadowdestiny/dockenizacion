<?php
namespace app\controllers;
use app\services\LanguageService;
use Phalcon\Di;
use stdClass;

class TestController extends ControllerBase
{
    public function languageListAction()
    {
        $this->noRender();
        $ls = new LanguageService($this->entityManager);
        var_dump($ls->availableLanguages());
    }
}

