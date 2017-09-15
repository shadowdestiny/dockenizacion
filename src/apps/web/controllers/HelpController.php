<?php


namespace EuroMillions\web\controllers;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;

class HelpController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $this->tag->prependTitle($translationAdapter->query('howto_em_name'));
        MetaDescriptionTag::setDescription($translationAdapter->query('howto_em_desc'));
    }
}
