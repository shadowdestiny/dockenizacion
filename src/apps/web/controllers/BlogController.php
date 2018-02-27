<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;

class BlogController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        var_dump('indexBlog');
        $this->tag->prependTitle($this->languageService->translate('contact_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('contact_desc'));

        return $this->view->setVars([
            'msg' => '',
        ]);
    }

    public function postAction()
    {
        var_dump($this->router->getParams()[0]);
        $this->tag->prependTitle($this->languageService->translate('contact_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('contact_desc'));

        return $this->view->setVars([
            'msg' => '',
        ]);
    }
}
