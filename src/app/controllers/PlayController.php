<?php
namespace EuroMillions\controllers;

class PlayController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $jackpot = $this->userService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        return $this->view->setVar('jackpot_value', $jackpot->getAmount()/100);
    }
}