<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\dto\OrderDTO;
use EuroMillions\web\vo\dto\UserDTO;

class ResultController extends PublicSiteControllerBase
{

    public function successAction()
    {
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrency($this->lotteryService->getNextJackpot('EuroMillions'));
        $user_id = $this->authService->getCurrentUser()->getId();
        /** @var User $user */
        $user = $this->userService->getUser($user_id);
        $result_order = $this->cartService->get($user_id);
        $order_dto = new OrderDTO($result_order->getValues());
        $this->view->pick('/cart/success');
	    $this->tag->prependTitle('Purchase Confirmation');
        $date_next_draw = $this->lotteryService->getNextDateDrawByLottery('EuroMillions');
        $date_time_util = new DateTimeUtil();
        return $this->view->setVars([
            'order' => $order_dto,
            'jackpot_value' => ViewHelper::formatJackpotNoCents($jackpot),
            'user' => new UserDTO($user),
            'start_draw_date_format' => date('D j M Y',$order_dto->getStartDrawDate()->getTimestamp()),
            'countdown_next_draw' => $date_time_util->getCountDownNextDraw($date_next_draw),
        ]);
    }

    public function failureAction()
    {
        $user_id = $this->authService->getCurrentUser()->getId();
        $play_service = $this->domainServiceFactory->getPlayService();
        $play_service->removeStorePlay($user_id);
        $play_service->removeStoreOrder($user_id);
        $this->view->pick('/cart/fail');
	    $this->tag->prependTitle('Payment Unsuccessful');
    }

}
