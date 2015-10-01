<?php


namespace EuroMillions\controllers;


use EuroMillions\vo\dto\PlayConfigDTO;

class AccountController extends PublicSiteControllerBase
{

    public function gamesAction()
    {
        $user = $this->authService->getCurrentUser();
        $jackpot = $this->userService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $myGames = null;
        $playConfigDTOCollection = [];

        if(!empty($user)){
            $myGames = $this->userService->getMyPlays($user->getId());
            foreach($myGames->getValues() as $game){
                $playConfigDTOCollection[] = new PlayConfigDTO($game);
            }
        }

        return $this->view->setVars([
            'my_games' => $playConfigDTOCollection,
            'jackpot_value' => $jackpot->getAmount()/100
        ]);
    }

}