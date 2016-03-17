<?php
namespace EuroMillions\web\services\email_templates_strategies;

use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\LotteryService;

class LongPlayEndedDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
{

    protected $lotteryService;

    protected $time_config;

    public function __construct(LotteryService $lotteryService = null)
    {
        $this->lotteryService = ($lotteryService != null) ? $lotteryService : \Phalcon\Di::getDefault()->get('domainServiceFactory')->getLotteriesDataService();
        $this->time_config = \Phalcon\Di::getDefault()->get('globalConfig')['retry_validation_time'];
    }

    public function getData(IEmailTemplateDataStrategy $strategy = null)
    {
        try {
            return [
                'jackpot_amount' => $strategy->getData()['jackpot_amount'],
            ];

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }
}