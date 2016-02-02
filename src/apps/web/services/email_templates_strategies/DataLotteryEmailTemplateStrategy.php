<?php


namespace EuroMillions\web\services\email_templates_strategies;

use EuroMillions\web\interfaces\EmailTemplateDataStrategy;
use EuroMillions\web\services\LotteriesDataService;

class DataLotteryEmailTemplateStrategy implements EmailTemplateDataStrategy
{

    protected $lotteriesDataService;

    protected $time_config;

    public function __construct(LotteriesDataService $lotteriesDataService = null)
    {
        $this->lotteriesDataService = ($lotteriesDataService != null) ? $lotteriesDataService : \Phalcon\Di::getDefault()->get('domainServiceFactory')->getLotteriesDataService();
        $this->time_config = \Phalcon\Di::getDefault()->get('globalConfig')['retry_validation_time'];
    }

    public function getData()
    {

        try {
            $next_draw_day = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
            $draw_day_format_one = $next_draw_day->format('l');
            $draw_day_format_two = $next_draw_day->format('j F Y');
            $draw_results = $this->lotteriesDataService->getLastResult('EuroMillions');
            $jackpot_amount = $this->lotteriesDataService->getNextJackpot('EuroMillions');
            $last_draw_date = $this->lotteriesDataService->getLastDrawDate('EuroMillions');

            return [
               'jackpot_amount' => $jackpot_amount,
               'draw_day_format_one' => $draw_day_format_one,
               'draw_day_format_two' => $draw_day_format_two,
               'time_close' => $this->time_config->time,
               'draw_result' => $draw_results,
               'last_draw_date' => $last_draw_date
            ];
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}