<?php


namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\LotteriesDataService;

class LatestResultsDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
{

    protected $lotteriesDataService;

    protected $time_config;

    public function __construct(LotteriesDataService $lotteriesDataService = null)
    {
        $this->lotteriesDataService = ($lotteriesDataService != null) ? $lotteriesDataService : \Phalcon\Di::getDefault()->get('domainServiceFactory')->getLotteriesDataService();
        $this->time_config = \Phalcon\Di::getDefault()->get('globalConfig')['retry_validation_time'];
    }

    public function getData(IEmailTemplateDataStrategy $strategy = null)
    {
        try {

            $draw_result = $this->lotteriesDataService->getLastResult('EuroMillions');
            $last_draw_date = $this->lotteriesDataService->getLastDrawDate('EuroMillions');

            return [
                'draw_result' => $draw_result,
                'last_draw_date' => $last_draw_date,
                'jackpot_amount' => $strategy->getData()['jackpot_amount']
            ];

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }
}