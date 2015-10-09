<?php


namespace EuroMillions\tasks;


use EuroMillions\services\DomainServiceFactory;
use EuroMillions\services\LotteriesDataService;
use EuroMillions\services\PriceCheckoutService;
use EuroMillions\services\ServiceFactory;
use EuroMillions\vo\EuroMillionsDrawBreakDown;
use EuroMillions\vo\ServiceActionResult;
use Money\Money;

class PriceCheckoutTask extends TaskBase
{
    /** @var  PriceCheckoutService */
    private $priceCheckoutService;

    /** @var  LotteriesDataService */
    private $lotteriesDataService;

    public function initialize(PriceCheckoutService $priceCheckoutService = null, LotteriesDataService $lotteriesDataService = null)
    {
        $domainFactory = new DomainServiceFactory($this->getDI(), new ServiceFactory($this->getDI()));
        ($priceCheckoutService) ? $this->priceCheckoutService = $priceCheckoutService : $domainFactory->getPriceCheckoutService();
        ($lotteriesDataService) ? $this->lotteriesDataService = $lotteriesDataService : $this->lotteriesDataService = $domainFactory->getLotteriesDataService();
        parent::initialize();
    }

    public function checkoutAction(\DateTime $today = null)
    {
        if(!$today) $today = new \DateTime();
        $lottery_name = 'EuroMillions';

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
                }
            }
        }
    }

}