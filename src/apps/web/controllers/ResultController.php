<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\entities\User;
use EuroMillions\web\vo\dto\OrderDTO;
use EuroMillions\web\vo\dto\UserDTO;

class ResultController extends PublicSiteControllerBase
{

    public function successAction()
    {
        $user_id = $this->authService->getCurrentUser()->getId();
        /** @var User $user */
        $user = $this->userService->getUser($user_id);
        //$play_service = $this->domainServiceFactory->getPlayService();
        $result_order = $this->cartService->get($user_id);
//        $play_service->removeStorePlay($user_id);
//        $play_service->removeStoreOrder($user_id);
        $this->view->pick('/cart/success');
        $order_dto = new OrderDTO($result_order->getValues());
        return $this->view->setVars([
            'order' => $order_dto,
            'user' => new UserDTO($user),
            'start_draw_date_format' => date('D j M Y',$order_dto->getStartDrawDate()->getTimestamp())
        ]);
    }

    public function failureAction()
    {
        $user_id = $this->authService->getCurrentUser()->getId();
        $play_service = $this->domainServiceFactory->getPlayService();
        $play_service->removeStorePlay($user_id);
        $play_service->removeStoreOrder($user_id);
        $this->view->pick('/cart/fail');
    }

}