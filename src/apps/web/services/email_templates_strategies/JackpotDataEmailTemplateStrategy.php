<?php


namespace EuroMillions\web\services\email_templates_strategies;

use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\LotteryService;

class JackpotDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
{

    protected $lotteriesDataService;

    protected $time_config;

    public function __construct(LotteryService $lotteryService = null)
    {
        $this->lotteriesDataService = ($lotteryService != null) ? $lotteryService : \Phalcon\Di::getDefault()->get('domainServiceFactory')->getLotteriesDataService();
        $this->time_config = \Phalcon\Di::getDefault()->get('globalConfig')['retry_validation_time'];
    }

    public function getData(IEmailTemplateDataStrategy $strategy = null)
    {
        try {

            $next_draw_day = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
            $draw_day_format_one = $next_draw_day->format('l');
            $draw_day_format_two = $next_draw_day->format('j F Y');
            $jackpot_amount = $this->lotteriesDataService->getNextJackpot('EuroMillions');

            return [
               'jackpot_amount' => $jackpot_amount,
               'draw_day_format_one' => $draw_day_format_one,
               'draw_day_format_two' => $draw_day_format_two,
               'time_close' => $this->time_config->time,
            ];
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}