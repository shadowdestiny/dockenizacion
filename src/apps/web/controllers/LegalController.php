<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;

class LegalController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $this->tag->prependTitle($this->languageService->translate('terms_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('terms_desc'));
        $this->view->setVar('pageController', 'legalIndex');
    }

    public function privacyAction()
    {
        $this->tag->prependTitle($this->languageService->translate('Privacy Policy'));
        MetaDescriptionTag::setDescription($this->languageService->translate('privacy_desc'));
        $this->view->setVar('pageController', 'legalPrivacy');
    }

    public function aboutAction()
    {
        $this->tag->prependTitle($this->languageService->translate('About Us'));
        MetaDescriptionTag::setDescription($this->languageService->translate('aboutus_desc'));
        $this->view->setVar('pageController', 'legalAbout');
    }

    public function cookiesAction()
    {
        $this->tag->prependTitle($this->languageService->translate('Cookies'));
        MetaDescriptionTag::setDescription($this->languageService->translate('cookies_desc'));
        $this->view->setVar('pageController', 'legalCookies');
    }
}
