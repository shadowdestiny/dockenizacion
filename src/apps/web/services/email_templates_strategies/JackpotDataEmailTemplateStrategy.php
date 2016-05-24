<?php


namespace EuroMillions\web\services\email_templates_strategies;

use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\LotteryService;
use Money\Currency;
use Money\Money;

class JackpotDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
{

    protected $lotteriesDataService;

    protected $time_config;

    public function __construct(LotteryService $lotteryService = null)
    {
        $this->lotteriesDataService = ($lotteryService != null) ? $lotteryService : \Phalcon\Di::getDefault()->get('domainServiceFactory')->getLotteryService();
        $this->time_config = \Phalcon\Di::getDefault()->get('config')['retry_validation_time'];
    }

    public function getData(IEmailTemplateDataStrategy $strategy = null)
    {
        try {

            $next_draw_day = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
            $draw_day_format_one = $next_draw_day->format('l');
            $draw_day_format_two = $next_draw_day->format('j F Y');
            $jackpot_amount = $this->lotteriesDataService->getNextJackpot('EuroMillions');
            $moneyFormatter = new MoneyFormatter();
            $jackpot_amount = $moneyFormatter->toStringByLocale('en_US', new Money((int) $jackpot_amount->getAmount(), new Currency('EUR')));
            return  [
               'jackpot_amount' => substr($jackpot_amount, 0, strpos($jackpot_amount, ".")),
               'draw_day_format_one' => $draw_day_format_one,
               'draw_day_format_two' => $draw_day_format_two,
               'time_close' => $this->time_config->time,
            ];
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}