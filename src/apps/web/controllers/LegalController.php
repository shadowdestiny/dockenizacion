<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;

class LegalController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $this->tag->prependTitle($translationAdapter->query('terms_name'));
        MetaDescriptionTag::setDescription($translationAdapter->query('terms_desc'));
    }

    public function privacyAction()
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $this->tag->prependTitle($translationAdapter->query('Privacy Policy'));
        MetaDescriptionTag::setDescription($translationAdapter->query('privacy_desc'));

    }

    public function aboutAction()
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $this->tag->prependTitle($translationAdapter->query('About Us'));
        MetaDescriptionTag::setDescription($translationAdapter->query('aboutus_desc'));

    }

    public function cookiesAction()
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));


        $this->tag->prependTitle($translationAdapter->query('Cookies'));
        MetaDescriptionTag::setDescription($translationAdapter->query('cookies_desc'));

    }
}
