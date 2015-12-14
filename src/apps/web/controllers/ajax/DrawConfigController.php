<?php


namespace EuroMillions\web\controllers\ajax;


class DrawConfigController extends AjaxControllerBase
{

    public function drawDatesAction()
    {
        $play_dates = $this->domainServiceFactory->getLotteriesDataService()->getRecurrentDrawDates('EuroMillions');
        if(!empty($play_dates)) {
            echo json_encode(['dates'=> $play_dates]);
        }
    }

}