<?php


namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\CurrencyConversionService;
use EuroMillions\web\services\LotteryService;

class LatestResultsDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
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
            $draw_result = $this->lotteryService->getLastResult('EuroMillions');
            $last_draw_date = $this->lotteryService->getLastDrawDate('EuroMillions');
            $jackpotStrategy = new JackpotDataEmailTemplateStrategy($this->lotteryService);
            return [
                'draw_result' => $draw_result,
                'last_draw_date' => $last_draw_date,
                'jackpot_amount' => $strategy->getData($jackpotStrategy)['jackpot_amount'],
                'draw_day_format_one' => $strategy->getData($jackpotStrategy)['draw_day_format_one'],
                'draw_day_format_two' => $strategy->getData($jackpotStrategy)['draw_day_format_two']
            ];

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }
}