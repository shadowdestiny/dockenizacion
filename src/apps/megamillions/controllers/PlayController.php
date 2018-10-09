<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/10/18
 * Time: 12:51
 */

namespace EuroMillions\megamillions\controllers;


class PlayController extends \EuroMillions\shared\controllers\PlayController
{

    public function indexAction()
    {

        var_dump(__LINE__);die();
        /*parent::indexAction();

        return $this->view->setVars([
            'play_dates' => $play_dates,
            'next_draw' => $dayOfWeek,
            'next_draw_format' => $draw->format('l j M G:i'),
            'currency_symbol' => $currency_symbol,
            'openTicket' => ($checkOpenTicket) ? '1' : '0',
            'single_bet_price' => $single_bet_price_currency->getAmount() / 100,
            'bet_price' => $bet_value,
            'automatic_random' => isset($automatic_random) ? true : false,
            'discount_lines_title' => 'Choose your bundle',
            'discount_lines' => json_encode($bundlePriceDTO),
            'draws_number' => $bundlePriceDTO->bundlePlayDTOActive->getDraws(),
            'discount' => $bundlePriceDTO->bundlePlayDTOActive->getDiscount(),
            'pageController' => 'euroPlay',
            'next_draw_date_format' => $draw->format($this->languageService->translate('dateformat')),
            'draw_day' => $this->languageService->translate($draw->format('l')),
        ]);*/

    }

}