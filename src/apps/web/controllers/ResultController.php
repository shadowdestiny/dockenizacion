<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\TrackingCodesHelper;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\dto\OrderDTO;
use EuroMillions\web\vo\dto\UserDTO;

class ResultController extends PublicSiteControllerBase
{

    public function successAction()
    {
        $params = $this->router->getParams();
        $lotteryName = $this->lotteryService->getLotteryConfigByName($params['lottery'])->getName();
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot($lotteryName));
        $this->view->setVar('jackpot_value_success', ViewHelper::formatJackpotNoCents($jackpot));
        $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $this->view->setVar('milliards', false);
        $this->view->setVar('trillions', false);
        if ($numbers > 1000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('milliards', true);
        } elseif ($numbers > 1000000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('trillions', true);
        } else{
            $this->view->setVar('milliards', false);
            $this->view->setVar('trillions', false);
        }
        $this->view->setVar('language', $this->languageService->getLocale());
        $user_id = $this->authService->getCurrentUser()->getId();
        /** @var User $user */
        $user = $this->userService->getUser($user_id);
        $result_order = $this->cartService->get($user_id, $lotteryName);
        $order_dto = new OrderDTO($result_order->getValues());
        $this->view->pick('/cart/success');
	    $this->tag->prependTitle('Purchase Confirmation');
        $date_next_draw = $this->lotteryService->getNextDateDrawByLottery($lotteryName);
        $date_time_util = new DateTimeUtil();
        /** @var \DateTime $actualDate */
        $actualDate = $order_dto->getStartDrawDate();

        //TJ pixel success buy
        $randomNumber = time() . mt_rand(1000, 9999999);
        $currentPage = substr($_SERVER["REQUEST_URI"], 0, 255);
        $userEmail = $this->authService->getCurrentUser()->getEmail()->toNative();
        $linkPlay = 'link_'.$lotteryName.'_play';
        return $this->view->setVars([
            'order' => $order_dto,
            'lottery_name' => $lotteryName,
            'play_link' => $linkPlay,
            'user' => new UserDTO($user),
            'start_draw_date_format' => $actualDate->format($this->languageService->translate('dateformat')),
            'draw_day' => $actualDate->format('l'),
            'countdown_next_draw' => $date_time_util->getCountDownNextDraw($date_next_draw),
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery($lotteryName)->modify('-1 hours')->format('Y-m-d H:i:s'),
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery($lotteryName)->modify('-1 hours'))->format('%a'),
            'random_number' => $randomNumber,
            'current_page' => $currentPage,
            'user_email' => $userEmail,
            'tracking' => TrackingCodesHelper::trackingAffiliatePlatformCodeWhenPurchaseIsSuccessfully($order_dto->getTotal(),'',strtolower($lotteryName))
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
