<?php


namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\LotteriesDataService;

class LowBalanceDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
{


    protected $lotteriesDataService;

    public function __construct(LotteriesDataService $lotteriesDataService = null)
    {
        $this->lotteriesDataService = ($lotteriesDataService != null) ? $lotteriesDataService : \Phalcon\Di::getDefault()->get('domainServiceFactory')->getLotteriesDataService();
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
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }
}