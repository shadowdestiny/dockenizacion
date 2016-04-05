<?php


namespace EuroMillions\web\tasks;

use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\services\UserService;
use EuroMillions\shared\vo\results\ActionResult;


class BetTask extends TaskBase
{
    /** @var  LotteryService */
    private $lotteryService;

    /** @var  PlayService */
    private $playService;

    /** @var  EmailService */
    private $emailService;

    /** @var  UserService */
    private $userService;

    public function initialize(LotteryService $lotteryService = null, PlayService $playService= null, EmailService $emailService = null, UserService $userService = null)
    {
        parent::initialize();
        $domainFactory = new DomainServiceFactory($this->getDI(),new ServiceFactory($this->getDI()));
        $this->lotteryService = $lotteryService ?: $domainFactory->getLotteryService();
        ($playService) ? $this->playService = $playService : $this->playService = $domainFactory->getPlayService();
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
    }


    public function placeBetsAction( $args = null )
    {
        if(!$args) {
            $date = new \DateTime();
        } else {
            $date = new \DateTime($args[0]);
        }
        $lotteries = $this->lotteryService->getLotteriesOrderedByNextDrawDate();
        foreach( $lotteries as $lottery ) {
            $this->lotteryService->placeBetForNextDraw($lottery, $date);
        }
    }


    public function longTermNotificationAction(\DateTime $today = null)
    {
        if(!$today) {
            $today = new \DateTime();
        }
        /** @var ActionResult $result_play_config */
        $result_play_config = $this->playService->getPlayConfigWithLongEnded($today);
        if($result_play_config->success()) {
            $this->userService->checkLongTermAndSendNotification($result_play_config->getValues(),$today);
        }
    }
}