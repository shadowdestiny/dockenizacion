<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 11/10/18
 * Time: 02:31 PM
 */

namespace EuroMillions\megamillions\controllers;

use EuroMillions\shared\components\widgets\JackpotAndCountDownWidget;
use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\repositories\TranslationDetailRepository;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\megamillions\vo\dto\MegaMillionsDrawBreakDownDTO;
use EuroMillions\shared\controllers\PublicSiteControllerBase;
use Money\Currency;
use Money\Money;

final class MegamillionsNumbersController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $date = $this->request->get('date');
        $lotteryName = 'MegaMillions';
        $date = empty($date) ? $this->lotteryService->getLastDrawDate($lotteryName) : new \DateTime($date);
        $webLanguageStrategy = new WebLanguageStrategy($this->session,$this->di->get('request'));
        $result = $this->lotteryService->getLotteryDrawsDTO('MegaMillions', 1000, $webLanguageStrategy);
        $draw_result = $this->lotteryService->getLastDrawWithBreakDownByDate($lotteryName, $date);
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('MegaMillions'));
        $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $params = ViewHelper::setSemanticJackpotValue($numbers, $letters, $jackpot, $this->languageService->getLocale());
        $this->view->setVar('milliards', $params['milliards']);
        $this->view->setVar('trillions', $params['trillions']);
        $this->view->setVar('jackpot_value', $params['jackpot_value']);
        $this->view->setVar('language', $this->languageService->getLocale());

        if (!$result->success()) {
            return $this->view->setVars([
                'error' => $result->errorMessage()
            ]);
        }
        /** @var EuroMillionsDraw $euroMillionsDraw */
        $euroMillionsDraw = $draw_result->getValues();
        $params['show_s_days'] = (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('MegaMillions')->modify('-1 hours'))->format('%a');

        $raffle = $euroMillionsDraw->getRaffle()->toArray();
        $raffle = $raffle['value'];
        //$breakDownDTO = new EuroMillionsDrawBreakDownDTO($euroMillionsDraw->getBreakDown());
        $breakDownDTO = new MegaMillionsDrawBreakDownDTO($euroMillionsDraw->getBreakDown());
        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());
        $this->tag->prependTitle($this->languageService->translate('results_pow_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('results_pow_desc'));
        $this->view->pick('numbers/index');

        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => $euroMillionsDraw->getId(),
            'last_result' => ['regular_numbers' => $euroMillionsDraw->getResult()->getRegularNumbersArray(), 'lucky_numbers' => $euroMillionsDraw->getResult()->getLuckyNumbersArray(), 'megaplier_play' => $raffle],
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('MegaMillions')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'last_draw_date' => $euroMillionsDraw->getDrawDate()->format($this->languageService->translate('dateformat')),
            'draw_day' => $euroMillionsDraw->getDrawDate()->format('l'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'list_draws' => $result->getValues(),
            'show_s_days' => $params['show_s_days'],
            'actual_year' => (new \DateTime())->format('Y'),
            'pageController' => 'megamillionsNumbersIndex',
        ]);

    }

    public function pastListAction()
    {

        $webLanguageStrategy = new WebLanguageStrategy($this->session,$this->di->get('request'));
        $result = $this->lotteryService->getLotteryDrawsDTO('MegaMillions', 1000, $webLanguageStrategy);
        if (!$result->success()) {
            return $this->view->setVars([
                'error' => $result->errorMessage()
            ]);
        }
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('MegaMillions'));
        $this->view->setVar('jackpot_value', ViewHelper::formatJackpotNoCents($jackpot));
        $numbers = preg_replace('/[A-Z,.]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/','',ViewHelper::formatJackpotNoCents($jackpot));
        $this->view->setVar('milliards', false);
        $this->view->setVar('trillions', false);
        if ($numbers > 1000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('milliards', true);
        } elseif ($numbers > 1000000 && $this->languageService->getLocale() != 'es_ES') {
            $numbers = round(($numbers / 1000000), 1);
            $this->view->setVar('jackpot_value', $letters . ' ' . $numbers);
            $this->view->setVar('trillions', true);
        } else{
            $this->view->setVar('milliards', false);
            $this->view->setVar('trillions', false);
        }
        $this->view->setVar('language', $this->languageService->getLocale());
        $this->tag->prependTitle($this->languageService->translate('resultshist_pow_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('resultshist_pow_desc'));

        $this->view->pick('numbers/past');

        return $this->view->setVars([
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('MegaMillions')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'date_canonical' => $this->lotteryService->getNextDateDrawByLottery('MegaMillions')->modify('-1 hours')->format('Y-m-d'),
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('MegaMillions')->modify('-1 hours'))->format('%a'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'list_draws' => $result->getValues(),
            'pageController' => 'megamillionsNumbersPast',
        ]);
    }

    private function convertCurrency(array $break_downs)
    {
        $user_currency = $this->userPreferencesService->getCurrency();
        if (!empty($break_downs)) {
            foreach ($break_downs as &$breakDown) {
                $breakDown['megaMillionsPrize'] = $this->currencyConversionService->convert(new Money((int)$breakDown['megaMillionsPrize'], new Currency('EUR')), $user_currency)->getAmount() / 10000;
                if($breakDown['megaplierPrize']) {
                    $breakDown['megaplierPrize'] = $this->currencyConversionService->convert(new Money((int)$breakDown['megaplierPrize'], new Currency('EUR')), $user_currency)->getAmount() / 10000;
                }
            }
        }
        return $break_downs;
    }
}