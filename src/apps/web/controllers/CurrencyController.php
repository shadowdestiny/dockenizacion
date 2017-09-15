<?php

namespace EuroMillions\web\controllers;


use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;

class CurrencyController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $this->tag->prependTitle($translationAdapter->query('currencies_name'));
    }
}
