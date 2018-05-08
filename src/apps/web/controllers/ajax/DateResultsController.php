<?php

namespace EuroMillions\web\controllers\ajax;

class DateResultsController extends AjaxControllerBase
{
    public function getDrawDaysByDateAction()
    {
        $begindDate = new \DateTime($this->request->get('year') . '-' . $this->request->get('month') . '-01');
        $endDate = clone ($begindDate);
        $endDate->modify('+1 month')->modify('-1 day');
        $languageService = $this->domainServiceFactory->getLanguageService();

        $playDates = [];
        for($i = $begindDate; $i <= $endDate; $i->modify('+1 day')){
            if ($i->format('N') == 2 || $i->format('N') == 5) {
                $playDates[] = [
                    'url' => $languageService->translate('link_euromillions_draw_history') . '/' . $i->format('Y-m-d'),
                    'day' => $i->format('d'),
                    'name' => $languageService->translate($i->format('l'))
                ];
            }
        }

        echo json_encode($playDates);
    }

    public function getPowerballDrawDaysByDateAction()
    {
        $begindDate = new \DateTime($this->request->get('year') . '-' . $this->request->get('month') . '-01');
        $endDate = clone ($begindDate);
        $endDate->modify('+1 month')->modify('-1 day');
        $languageService = $this->domainServiceFactory->getLanguageService();

        $playDates = [];
        for($i = $begindDate; $i <= $endDate; $i->modify('+1 day')){
            if ($i->format('N') == 3 || $i->format('N') == 6) {
                $playDates[] = [
                    'url' => 'powerball/results/draw-history-page' . '/' . $i->format('Y-m-d'),
                    'day' => $i->format('d'),
                    'name' => $languageService->translate($i->format('l'))
                ];
            }
        }

        echo json_encode($playDates);
    }

}