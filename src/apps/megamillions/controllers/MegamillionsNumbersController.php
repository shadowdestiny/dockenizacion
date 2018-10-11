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
use EuroMillions\web\vo\dto\MegaMillionsBreakDownDataDTO;
use EuroMillions\web\controllers\PublicSiteControllerBase;
use Money\Currency;
use Money\Money;

class MegamillionsNumbersController extends PublicSiteControllerBase
{
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

}