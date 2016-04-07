<?php
namespace EuroMillions\web\controllers;


use EuroMillions\web\entities\Lottery;
use EuroMillions\shared\vo\results\ActionResult;

class FaqController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $config = $this->di->get('config');
        /** @var ActionResult $result */
        $lottery = $this->lotteryService->getLotteryConfigByName('EuroMillions');

        return $this->view->setVars([
            'price_bet' => (!empty($lottery)) ? $lottery->getSingleBetPrice()->getAmount() / 10000 : "",
            'draw_time' => (!empty($lottery)) ? $lottery->getDrawTime() : '',
            'email_support' => $config->email_support['email'],
        ]);
    }

}