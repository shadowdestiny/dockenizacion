<?php


namespace EuroMillions\web\controllers\profile;


use EuroMillions\shared\components\widgets\PaginationWidget;

use EuroMillions\web\controllers\AccountController;
use EuroMillions\web\vo\dto\PastDrawsCollectionDTO;
use EuroMillions\web\vo\dto\UpcomingDrawsDTO;

class TicketsController extends AccountController
{

    /**
     * @return \Phalcon\Mvc\View
     */
    public function gamesAction()
    {
        $user = $this->authService->getLoggedUser();
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions'));
        $playConfigInactivesDTOCollection = [];
        $message_actives = '';
        $message_inactives = '';
        $playConfigDTO = null;

        $myGamesActives = $this->userService->getMyActivePlays($user->getId());
        if($myGamesActives->success()){
            $myGames = $myGamesActives->getValues();
            $playConfigDTO = new UpcomingDrawsDTO($myGames);
        }else{
            $message_actives = $myGamesActives->errorMessage();
        }
        $myGamesInactives = $this->userService->getMyInactivePlays($user->getId());
        if($myGamesInactives->success()){
            $playConfigInactivesDTOCollection = new PastDrawsCollectionDTO($myGamesInactives->getValues());
        }else{
            $message_inactives = $myGamesInactives->errorMessage();
        }

        $page = (!empty($this->request->get('page'))) ? $this->request->get('page') : 1;
        $paginator = $this->getPaginatorAsArray(!empty($playConfigInactivesDTOCollection->result['dates']) ? $playConfigInactivesDTOCollection->result['dates'] : [],4,$page);
        /** @var \Phalcon\Mvc\ViewInterface $paginator_view */
        $paginator_view = (new PaginationWidget($paginator, $this->request->getQuery()))->render();
        $this->view->pick('account/games');

        $this->tag->prependTitle('My Tickets');
        return $this->view->setVars([
            'my_games_actives' => $playConfigDTO,
            'my_games_inactives' => $paginator->getPaginate()->items,
            'my_subscription_actives' => $this->userService->getMyActiveSubscriptions($user->getId(), $this->lotteryService->getNextDateDrawByLottery('Euromillions')),
            'my_subscription_inactives' => $this->userService->getMyInactiveSubscriptions($user->getId()),
            'jackpot_value' => $jackpot,
            'paginator_view' => $paginator_view,
            'message_actives' => $message_actives,
            'message_inactives' => $message_inactives,
            'nextDrawDate' => $this->lotteryService->getNextDateDrawByLottery('Euromillions')->format('Y M d'),
        ]);
    }
}