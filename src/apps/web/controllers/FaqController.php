<?php
namespace EuroMillions\web\controllers;


use EuroMillions\web\entities\Lottery;
use EuroMillions\web\vo\ActionResult;

class FaqController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $config = $this->di->get('config');
        /** @var ActionResult $result */
        $result = $this->lotteriesDataService->getLotteryConfigByName('EuroMillions');

        $lottery = null;
        if($result->success()) {
            /** @var Lottery $lottery*/
            $lottery = $result->getValues();
        }

        return $this->view->setVars([
            'price_bet' => (!empty($lottery)) ? $lottery->getSingleBetPrice()->getAmount() / 10000 : "",
            'draw_time' => (!empty($lottery)) ? $lottery->getDrawTime() : '',
            'email_support' => $config->email_support['email'],
        ]);
    }

}