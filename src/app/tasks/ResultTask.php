<?php
namespace EuroMillions\tasks;
use Doctrine\ORM\EntityManager;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\entities\PlayConfig;
use EuroMillions\services\CurrencyService;
use EuroMillions\services\DomainServiceFactory;
use EuroMillions\services\EmailService;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\services\LotteriesDataService;
use EuroMillions\services\PlayService;
use EuroMillions\services\ServiceFactory;
use EuroMillions\services\UserService;
use EuroMillions\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\vo\EuroMillionsDrawBreakDown;
use Money\Currency;
use Money\Money;
use Phalcon\CLI\Task;
use Phalcon\Di;

class ResultTask extends TaskBase
{


    /** @var  LotteriesDataService */
    private $lotteriesDataService;

    /** @var  PlayService */
    private $playService;

    /** @var  EmailService */
    private $emailService;

    /** @var  UserService */
    private $userService;

    /** @var  CurrencyService */
    private $currencyService;


    public function initialize(LotteriesDataService $lotteriesDataService = null, PlayService $playService= null, EmailService $emailService = null, UserService $userService = null, CurrencyService $currencyService = null)
    {
        $domainFactory = new DomainServiceFactory($this->getDI(),new ServiceFactory($this->getDI()));
        ($lotteriesDataService) ? $this->lotteriesDataService = $lotteriesDataService : $this->lotteriesDataService = $domainFactory->getLotteriesDataService();
        ($playService) ? $this->playService = $playService : $this->playService = $domainFactory->getPlayService();
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        ($userService) ? $this->userService = $userService : $domainFactory->getUserService();
        ($currencyService) ? $this->currencyService = $currencyService : $domainFactory->getServiceFactory()->getCurrencyService();
        parent::initialize();
    }


    public function updateAction(\DateTime $today = null)
    {
        if(!$today) {
            $today = new \DateTime();
        }

        $this->lotteriesDataService->updateLastDrawResult('EuroMillions');
        $this->lotteriesDataService->updateLastBreakDown('EuroMillions');
        /** @var EuroMillionsDrawBreakDown $emBreakDownData */
        $draw = $this->lotteriesDataService->getBreakDownDrawByDate('EuroMillions',$today);
        $breakdown_list = null;
        if($draw->success()){
            $break_down_list = new EuroMillionsDrawBreakDownDTO($draw->getValues());
          //  $break_down_list = $this->convertCurrency($break_down_list->toArray(), new Currency('EUR'));

        }
        $result_play_config = $this->playService->getPlaysConfigToBet($today);
        if($result_play_config->success() && !empty($break_down_list)){
            /** @var PlayConfig[] $play_config_list */
            $play_config_list = $result_play_config->getValues();
            foreach($play_config_list as $play_config){
                $user = $this->userService->getUser($play_config->getUser()->getId());
                $this->emailService->sendTransactionalEmail($user,'latest-results');
            }
        }
    }

    //EMTD method inside BreakDownDTO
    private function convertCurrency(array $break_downs, Currency $user_currency = null)
    {
        if(empty($user_currency)){
            $user_currency = $this->userService->getCurrency();
        }

        if(!empty($break_downs)) {
            foreach($break_downs as &$breakDown) {
                //var_dump($breakDown['lottery_prize']);
                //new Money((int) $breakDown['lottery_prize'], new Currency('EUR')), $user_currency)
                $breakDown['lottery_prize'] = $this->currencyService->convert(new Money((int) $breakDown['lottery_prize'], new Currency('EUR')), $user_currency)->getAmount() / 10000;
            }
        }
        return $break_downs;
    }
}