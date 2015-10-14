<?php


namespace EuroMillions\tasks;


use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\entities\Lottery;
use EuroMillions\entities\PlayConfig;
use EuroMillions\entities\User;
use EuroMillions\exceptions\InvalidBalanceException;
use EuroMillions\services\DomainServiceFactory;
use EuroMillions\services\EmailService;
use EuroMillions\services\LotteriesDataService;
use EuroMillions\services\PlayService;
use EuroMillions\services\ServiceFactory;
use EuroMillions\services\UserService;

class BetTask extends TaskBase
{
    /** @var  LotteriesDataService */
    private $lotteriesDataService;

    /** @var  PlayService */
    private $playService;

    /** @var  EmailService */
    private $emailService;

    /** @var  UserService */
    private $userService;


    public function initialize(LotteriesDataService $lotteriesDataService = null, PlayService $playService= null, EmailService $emailService = null, UserService $userService = null)
    {
        $domainFactory = new DomainServiceFactory($this->getDI(),new ServiceFactory($this->getDI()));
        ($lotteriesDataService) ? $this->lotteriesDataService = $lotteriesDataService : $this->lotteriesDataService = $domainFactory->getLotteriesDataService();
        ($playService) ? $this->playService = $playService : $this->playService = $domainFactory->getPlayService();
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        ($userService) ? $this->userService = $userService : $domainFactory->getUserService();
        parent::initialize();
    }

    public function createBetAction(\DateTime $today = null)
    {
        if (!$today) $today = new \DateTime();

        $lotteryName = 'EuroMillions';
        //$playService = $this->domainServiceFactory->getPlayService();
        $result_euromillions_draw = $this->lotteriesDataService->getNextDrawByLottery($lotteryName);
        /** @var EuroMillionsDraw $euromillions_draw */
        $euromillions_draw = $result_euromillions_draw->getValues();
        $result_play_configs = $this->playService->getPlaysConfigToBet($result_euromillions_draw->getValues()->getDrawDate());

        if($result_play_configs->success()){
            /** @var PlayConfig[] $play_config_list */
            $play_config_list = $result_play_configs->getValues();
            /** @var User $user */
            $user = null;
            $user_id = '';
            foreach($play_config_list as $play_config) {
                if($play_config->getDrawDays()->compareTo($euromillions_draw->getDrawDate()->format('w'))){
                    try{
                        if(empty($user_id)){
                            $this->playService->bet($play_config, $euromillions_draw);
                        }
                        if(!empty($user_id) && $user_id != $play_config->getUser()->getId()->id()){
                            $user_id = '';
                            $this->playService->bet($play_config, $euromillions_draw);
                        }
                    }catch(InvalidBalanceException $e){
                        if(empty($user_id) || $user_id != $play_config->getUser()->getId()->id()){
                            $user = $this->userService->getUser($play_config->getUser()->getId());
                            $user_id = $play_config->getUser()->getId()->id();
                            $this->emailService->sendTransactionalEmail($user, 'low-balance');
                        }
                    }
                }
            }
        }
    }




}