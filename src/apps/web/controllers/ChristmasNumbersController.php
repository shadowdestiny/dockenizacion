<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\components\ViewHelper;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use Money\Currency;
use Money\Money;
use Phalcon\Di;

/**
 * @property void ChristmasService
 */
class ChristmasNumbersController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $this->tag->prependTitle($this->languageService->translate('results_ch_name'));
        MetaDescriptionTag::setDescription($this->languageService->translate('results_ch_desc'));
        $this->view->pick('/christmasNumbers/index');
    }

    public function searchAction()
    {
        $ticketNumber = $this->request->getPost('ticket_number');
        if ( is_numeric($ticketNumber) )
        {
            $ticket = $this->christmasService->getchristmasTicketAwardByNumber($ticketNumber);
            $this->view->pick('/christmasNumbers/checkNumber');
            return $this->view->setVars([
                'ticket_number' => $ticket,
            ]);
        } else {
            $this->view->pick('/christmasNumbers/index');
        }
    }
}
