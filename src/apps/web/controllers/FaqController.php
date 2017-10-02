<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\entities\Lottery;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;

class FaqController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        $config = $this->di->get('config');
        /** @var ActionResult $result */
        $lottery = $this->lotteryService->getLotteryConfigByName('EuroMillions');

        $this->tag->prependTitle($translationAdapter->query('faq_name'));
        MetaDescriptionTag::setDescription($translationAdapter->query('faq_desc'));

        return $this->view->setVars([
            'price_bet' => (!empty($lottery)) ? $lottery->getSingleBetPrice()->getAmount() / 10000 : "",
            'draw_time' => (!empty($lottery)) ? $lottery->getDrawTime() : '',
            'email_support' => $config->email_support['email'],
        ]);
    }

}
