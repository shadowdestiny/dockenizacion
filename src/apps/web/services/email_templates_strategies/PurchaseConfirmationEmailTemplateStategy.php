<?php


namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\LotteryService;

class PurchaseConfirmationEmailTemplateStategy implements IEmailTemplateDataStrategy
{
    protected $lotteryService;
    protected $time_config;

    public function __construct(LotteryService $lotteryService = null)
    {
        $this->lotteryService = ($lotteryService != null) ? $lotteryService : \Phalcon\Di::getDefault()->get('domainServiceFactory')->getLotteryService();
        $this->time_config = \Phalcon\Di::getDefault()->get('config')['retry_validation_time'];
    }

    public function getData(IEmailTemplateDataStrategy $strategy = null)
    {
        try {
            $draw_day_format_one = $strategy->getData()['draw_day_format_one'];
            $draw_day_format_two = $strategy->getData()['draw_day_format_two'];
            $jackpot_amount = $strategy->getData()['jackpot_amount'];

            return [
                'jackpot_amount' => $jackpot_amount,
                'draw_day_format_one' => $draw_day_format_one,
                'draw_day_format_two' => $draw_day_format_two,
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }
}