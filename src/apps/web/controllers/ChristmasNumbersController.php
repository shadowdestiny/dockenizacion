<?php

namespace EuroMillions\web\controllers;

use EuroMillions\web\components\tags\MetaDescriptionTag;

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
        return $this->view->setVars([
            'pageController' => 'christmasNumbersIndex',
        ]);
    }

    public function searchAction()
    {
        if (is_numeric($this->request->getPost('ticket_number'))) {
            $this->view->pick('/christmasNumbers/checkNumber');
            return $this->view->setVars([
                'ticket_number' => $this->christmasService->getchristmasTicketAwardByNumber($this->request->getPost('ticket_number')),
                'pageController' => 'christmasNumbersSearch',
            ]);
        }

        $this->view->pick('/christmasNumbers/index');
        return $this->view->setVars([
            'pageController' => 'christmasNumbersIndex',
        ]);
    }
}
