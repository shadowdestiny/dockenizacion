<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;

class HelpController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $this->tag->prependTitle($this->languageService->translate('howto_em_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('howto_em_desc'));
        $this->view->setVar('pageController', 'euroHelp');
    }
}
