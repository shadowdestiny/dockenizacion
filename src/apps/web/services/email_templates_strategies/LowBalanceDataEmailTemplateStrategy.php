<?php


namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;

class LowBalanceDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
{
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