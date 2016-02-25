<?php


namespace EuroMillions\web\tasks;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\WinEmailAboveTemplate;
use EuroMillions\web\emailTemplates\WinEmailTemplate;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\DomainServiceFactory;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\NullEmailTemplateDataStrategy;
use EuroMillions\web\services\email_templates_strategies\WinEmailAboveDataEmailTemplateStrategy;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\PriceCheckoutService;
use EuroMillions\web\services\ServiceFactory;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use Money\Currency;
use Money\Money;

class PriceCheckoutTask extends TaskBase
{
    const EMAIL_ABOVE = 2500*100;

    /** @var  PriceCheckoutService */
    private $priceCheckoutService;

    /** @var  LotteriesDataService */
    private $lotteriesDataService;

    /** @var  UserService  */
    private $userService;

    /** @var  EmailService */
    private $emailService;

    public function initialize(PriceCheckoutService $priceCheckoutService = null, LotteriesDataService $lotteriesDataService = null, EmailService $emailService = null, UserService $userService = null)
    {
        $domainFactory = new DomainServiceFactory($this->getDI(), new ServiceFactory($this->getDI()));
        $this->priceCheckoutService = $priceCheckoutService ? $this->priceCheckoutService = $priceCheckoutService : $domainFactory->getPriceCheckoutService();
        ($lotteriesDataService) ? $this->lotteriesDataService = $lotteriesDataService : $this->lotteriesDataService = $domainFactory->getLotteriesDataService();
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        ($userService) ? $this->userService = $userService : $domainFactory->getUserService();
        parent::initialize();
    }

    public function checkoutAction(\DateTime $today = null)
    {
        if(!$today) $today = new \DateTime();
        $lottery_name = 'EuroMillions';
        $config = $this->di->get('config');
        $threshold_price = new Money((int) $config->threshold_above['value'] * 100, new Currency('EUR'));
        $play_configs_result_awarded = $this->priceCheckoutService->playConfigsWithBetsAwarded($today);
        //get breakdown
        $result_breakdown = $this->lotteriesDataService->getBreakDownDrawByDate($lottery_name,$today);
        if($result_breakdown->success() && $play_configs_result_awarded->success()){
            /** @var EuroMillionsDrawBreakDown $euromillions_breakDown */
            $euromillions_breakDown = $result_breakdown->getValues();
            foreach($play_configs_result_awarded->getValues() as $play_config_and_count) {
                /** @var Money $result_amount */
                $result_amount = $euromillions_breakDown->getAwardFromCategory($play_config_and_count[1],$play_config_and_count[2]);
                if($result_amount->getAmount() > 0) {
                    /** @var User $user */
                    $user = $play_config_and_count[0]->getUser();
                    $this->priceCheckoutService->reChargeAmountAwardedToUser($user,$result_amount);
                    $emailTemplate = new EmailTemplate();
                    $emailTemplate = new WinEmailTemplate($emailTemplate, new NullEmailTemplateDataStrategy());
                    $emailTemplate->setUser($user);
                    $emailTemplate->setResultAmount($result_amount);
                    if($result_amount->greaterThanOrEqual($threshold_price)) {
                        $this->userService->userWonAbove($user, $result_amount);
                        $this->emailService->sendTransactionalEmail($user,$emailTemplate);
                    } else {
                        $this->emailService->sendTransactionalEmail($user,$emailTemplate);
                    }
                }
            }
        }
    }
}