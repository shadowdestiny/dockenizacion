<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 11/10/18
 * Time: 02:31 PM
 */

namespace EuroMillions\eurojackpot\controllers;

use EuroMillions\shared\components\widgets\JackpotAndCountDownWidget;
use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\repositories\TranslationDetailRepository;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\eurojackpot\vo\dto\EuroJackpotDrawBreakDownDTO;
use EuroMillions\shared\controllers\PublicSiteControllerBase;
use Money\Currency;
use Money\Money;

final class EuroJackpotNumbersController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $date = $this->router->getParams()['date'];
        $lotteryName = 'EuroJackpot';
        $date = empty($date) ? $this->lotteryService->getLastDrawDate($lotteryName) : new \DateTime($date);
        $webLanguageStrategy = new WebLanguageStrategy($this->session,$this->di->get('request'));
        $result = $this->lotteryService->getLotteryDrawsDTO('EuroJackpot', 1000, $webLanguageStrategy);
        $draw_result = $this->lotteryService->getLastDrawWithBreakDownByDate($lotteryName, $date);
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroJackpot'));
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
        $params['show_s_days'] = (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('EuroJackpot')->modify('-1 hours'))->format('%a');

        //$breakDownDTO = new EuroMillionsDrawBreakDownDTO($euroMillionsDraw->getBreakDown());
        $breakDownDTO = new EuroJackpotDrawBreakDownDTO($euroMillionsDraw->getBreakDown());
        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());
        $this->tag->prependTitle($this->languageService->translate('results_pow_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('results_pow_desc'));
        $this->view->pick('numbers/index');

        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => $euroMillionsDraw->getId(),
            'last_result' => ['regular_numbers' => $euroMillionsDraw->getResult()->getRegularNumbersArray(), 'lucky_numbers' => $euroMillionsDraw->getResult()->getLuckyNumbersArray()],
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('EuroJackpot')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'last_draw_date' => $euroMillionsDraw->getDrawDate()->format($this->languageService->translate('dateformat')),
            'draw_day' => $euroMillionsDraw->getDrawDate()->format('l'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'list_draws' => $result->getValues(),
            'show_s_days' => $params['show_s_days'],
            'actual_year' => (new \DateTime())->format('Y'),
            'pageController' => 'EuroJackpotNumbersIndex',
        ]);

    }

    public function pastListAction()
    {

        $webLanguageStrategy = new WebLanguageStrategy($this->session,$this->di->get('request'));
        $result = $this->lotteryService->getLotteryDrawsDTO('EuroJackpot', 1000, $webLanguageStrategy);
        if (!$result->success()) {
            return $this->view->setVars([
                'error' => $result->errorMessage()
            ]);
        }
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroJackpot'));
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
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('EuroJackpot')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'date_canonical' => $this->lotteryService->getNextDateDrawByLottery('EuroJackpot')->modify('-1 hours')->format('Y-m-d'),
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('EuroJackpot')->modify('-1 hours'))->format('%a'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'list_draws' => $result->getValues(),
            'pageController' => 'EuroJackpotNumbersPast',
        ]);
    }

    public function pastResultAction()
    {
        $params = $this->router->getParams();
        if (!isset($params[0])) {
            $this->response->redirect($this->lottery . 'results');
        }
        $jackpot = $this->userPreferencesService->getJackpotInMyCurrencyAndMillions($this->lotteryService->getNextJackpot('EuroJackpot'));

        $this->view->setVar('jackpot_value', ViewHelper::formatJackpotNoCents($jackpot));
        $numbers = preg_replace('/[A-Z,.]/', '', ViewHelper::formatJackpotNoCents($jackpot));
        $letters = preg_replace('/[0-9.,]/', '', ViewHelper::formatJackpotNoCents($jackpot));

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
        } else {
            $this->view->setVar('milliards', false);
            $this->view->setVar('trillions', false);
        }
        $this->view->setVar('language', $this->languageService->getLocale());
        $date = $params[0];
        $lotteryName = 'EuroJackpot';
        $actualDate = new \DateTime();
        $date = empty($date) ? new \DateTime() : new \DateTime($date);
        $draw_result = $this->lotteryService->getDrawWithBreakDownByDate($lotteryName, $date);
        $draw = $this->lotteryService->getNextDateDrawByLottery('Euromillions');
        /** @var EuroJackpotDraw $euroMillionsDraw */
        $euroMillionsDraw = $draw_result->getValues();
        $breakDownDTO = new EuroJackpotDrawBreakDownDTO($euroMillionsDraw->getBreakDown());
        $break_down_list = $this->convertCurrency($breakDownDTO->toArray());
//var_dump($break_down_list); exit;
        $this->tag->prependTitle($this->languageService->translate('resultsdate_em_name') . $this->languageService->translate($date->format('l')) . ', ' . $date->format($this->languageService->translate('dateformat')));
        MetaDescriptionTag::setDescription($this->languageService->translate('resultsdate_em_desc'));

        $this->view->pick('/numbers/past-draw');
        return $this->view->setVars([
            'break_downs' => !empty($break_down_list) ? $break_down_list : '',
            'id_draw' => $euroMillionsDraw->getId(),
            'last_result' => ['regular_numbers' => $euroMillionsDraw->getResult()->getRegularNumbersArray(), 'lucky_numbers' => $euroMillionsDraw->getResult()->getLuckyNumbersArray()],
            'last_draw_date' => $euroMillionsDraw->getDrawDate()->format('D, d M Y'),
            'date_canonical' => $euroMillionsDraw->getDrawDate()->format('Y-m-d'),
            'date_draw' => $this->lotteryService->getNextDateDrawByLottery('EuroJackpot')->modify('-1 hours')->format('Y-m-d H:i:s'),
            'symbol' => $this->userPreferencesService->getMyCurrencyNameAndSymbol()['symbol'],
            'show_s_days' => (new \DateTime())->diff($this->lotteryService->getNextDateDrawByLottery('EuroJackpot')->modify('-1 hours'))->format('%a'),
            'actual_year' => $actualDate->format('Y'),
            'pageController' => 'euroPastResult',
            'draw_day' => $euroMillionsDraw->getDrawDate()->format('l'),
            'next_draw_date_format' => $draw->format($this->languageService->translate('dateformat')),
            'past_draw_date_format' => $euroMillionsDraw->getDrawDate()->format('d.m.Y'),
        ]);

    }

    private function convertCurrency(array $break_downs)
    {
        $user_currency = $this->userPreferencesService->getCurrency();
        if (!empty($break_downs)) {
            foreach ($break_downs as &$breakDown) {
                $breakDown['EuroJackpotPrize'] = $this->currencyConversionService->convert(new Money((int)$breakDown['EuroJackpotPrize'], new Currency('EUR')), $user_currency)->getAmount() / 10000;
            }
        }
        return $break_downs;
    }
}