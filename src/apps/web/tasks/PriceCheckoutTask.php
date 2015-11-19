<?php


namespace EuroMillions\web\tasks;


use EuroMillions\web\services\DomainServiceFactory;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\PriceCheckoutService;
use EuroMillions\web\services\ServiceFactory;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use Money\Money;

class PriceCheckoutTask extends TaskBase
{
    const EMAIL_ABOVE = 1500*100;

    /** @var  PriceCheckoutService */
    private $priceCheckoutService;

    /** @var  LotteriesDataService */
    private $lotteriesDataService;

    /** @var  EmailService */
    private $emailService;

    public function initialize(PriceCheckoutService $priceCheckoutService = null, LotteriesDataService $lotteriesDataService = null, EmailService $emailService = null)
    {
        $domainFactory = new DomainServiceFactory($this->getDI(), new ServiceFactory($this->getDI()));
        ($priceCheckoutService) ? $this->priceCheckoutService = $priceCheckoutService : $domainFactory->getPriceCheckoutService();
        ($lotteriesDataService) ? $this->lotteriesDataService = $lotteriesDataService : $this->lotteriesDataService = $domainFactory->getLotteriesDataService();
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        parent::initialize();
    }

    public function checkoutAction(\DateTime $today = null)
    {
        if(!$today) $today = new \DateTime();
        $lottery_name = 'EuroMillions';
        $config = $this->di->get('config');
        $threshold_price = (int) $config->threshold_above['value'] * 100;
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
                    $user = $play_config_and_count[0]->getUser();
                    $this->priceCheckoutService->reChargeAmountAwardedToUser($user,$result_amount);
                    if($result_amount->getAmount() > $threshold_price) {
                        $this->emailService->sendTransactionalEmail($user,'win-email-above-1500', $this->getVarsToEmailTemplate('Test1'));
                    }else{
                        $this->emailService->sendTransactionalEmail($user,'win-email',$this->getVarsToEmailTemplate('Test1'));
                    }
                }
            }
        }
    }


    private function getVarsToEmailTemplate($subject)
    {
        //vars email template
        $vars = [
            'subject' => $subject,
            'template_vars' =>
                [
                    [
                        'name'    => 'test',
                        'content' => 'Test'
                    ]
                ]
        ];

        return $vars;
    }

}