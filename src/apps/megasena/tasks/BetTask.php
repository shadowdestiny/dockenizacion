<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 14/11/18
 * Time: 03:44 PM
 */

namespace EuroMillions\eurojackpot\tasks;

use EuroMillions\web\tasks\TaskBase;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\services\UserService;
use EuroMillions\shared\tasks\BetTask as bt;

class BetTask extends TaskBase
{
    use bt;
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

    public function placeEuroJackpotBetsAction($args = null)
    {
        $this->placeLotteryBets('EuroJackpot', $args);
    }
}