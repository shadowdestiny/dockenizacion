<?php


namespace EuroMillions\web\emailTemplates;

use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\email_templates_strategies\LatestResultsDataEmailTemplateStrategy;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use Money\Currency;
use Money\Money;
use EuroMillions\web\entities\User;


class LatestResultsPowerBallEmailTemplate extends EmailTemplateDecorator
{

    protected $break_down_list;

    /** @var  User $user */
    protected $user;

    public function loadVars(IEmailTemplateDataStrategy $strategy = null)
    {
        $strategy = $strategy ? $strategy : new LatestResultsDataEmailTemplateStrategy();
        $data = $this->emailTemplateDataStrategy->getData($strategy);
        $draw_result = $data['draw_result'];
        $jackpot = $data['jackpot_amount'];
        $last_draw_date = $data['last_draw_date'];
        $language = $this->user->getDefaultLanguage();

        if ($language == "ru") {
            // Welcome Email Russian Version Template ID= 3997341
            $template_id = "7329039";
            $subject = 'Результаты розыгрышей последней лотереи';
        } else {
            // Welcome Email English Version Template ID= 4021147
            $template_id = "7329181";
            $subject = 'Latest results';
        }


        $vars = [
            //'template' => '624601', // Old template email ID
            'template' => $template_id,
            'subject' => $subject,
            'vars' =>
                [
                    [
                        'name' => 'breakdown',
                        'content' => $this->getBreakDownList()
                    ],
                    [
                        'name' => 'jackpot_amount',
                        'content' => $jackpot
                    ],
                    [
                        'name' => 'draw_date',
                        'content' => $last_draw_date->format('j F Y')
                    ],
                    [
                        'name' => 'regular_numbers',
                        'content' => $this->mapNumbers($draw_result['regular_numbers'])
                    ],
                    [
                        'name' => 'lucky_numbers',
                        'content' => $this->mapLuckyNumbers($draw_result['lucky_numbers'])
                    ],
                    [
                        'name' => 'draw_day_format_one',
                        'content' => $data['draw_day_format_one']
                    ],
                    [
                        'name' => 'draw_day_format_two',
                        'content' => $data['draw_day_format_two']
                    ],
                ]
        ];

        return $vars;
    }

    /**
     * @return EuroMillionsDrawBreakDownDTO
     */
    public function getBreakDownList()
    {
        $euromillionsBreakDownDTO = $this->break_down_list;
        $break = [
            [
                'ball5' => '1',
                'star2' => '1',
                'winners' => $euromillionsBreakDownDTO->category_one->winners,
                'lottery_prize' => $this->currencyConversionAndFormatted($euromillionsBreakDownDTO->category_one->lottery_prize)
            ],
            [
                'ball5' => '1',
                'star1' => '1',
                'winners' => $euromillionsBreakDownDTO->category_two->winners,
                'lottery_prize' => $this->currencyConversionAndFormatted($euromillionsBreakDownDTO->category_two->lottery_prize)
            ],
            [
                'ball4' => '1',
                'star2' => '1',
                'winners' => $euromillionsBreakDownDTO->category_three->winners,
                'lottery_prize' => $this->currencyConversionAndFormatted($euromillionsBreakDownDTO->category_three->lottery_prize)
            ],
            [
                'ball4' => '1',
                'star1' => '1',
                'winners' => $euromillionsBreakDownDTO->category_four->winners,
                'lottery_prize' => $this->currencyConversionAndFormatted($euromillionsBreakDownDTO->category_four->lottery_prize)
            ],
            [
                'ball4' => '1',
                'star0' => '1',
                'winners' => $euromillionsBreakDownDTO->category_five->winners,
                'lottery_prize' => $this->currencyConversionAndFormatted($euromillionsBreakDownDTO->category_five->lottery_prize)
            ],
        ];

        return $break;
    }

    public function mapNumbers(array $numbers)
    {
        $numbersToEmail = [];

        foreach ($numbers as $number) {
            $numbersToEmail[]['number'] = (int)$number;
        }
        return $numbersToEmail;
    }

    public function mapLuckyNumbers(array $numbers)
    {
        $numbersToEmail = [];
        $numbersToEmail[]['number'] = (int)$numbers[1];

        return $numbersToEmail;
    }

    private function currencyConversionAndFormatted($amount)
    {
        $moneyFormatter = new MoneyFormatter();
        return $moneyFormatter->toStringByLocale('en_US', new Money((int)$amount, new Currency('EUR')));
    }

    /**
     * @param mixed $break_down_list
     */
    public function setBreakDownList($break_down_list)
    {
        $this->break_down_list = $break_down_list;
    }

    public function loadHeader()
    {
        return $this->emailTemplate->loadHeader();
    }

    public function loadFooter()
    {
        return $this->emailTemplate->loadFooter();
    }
}