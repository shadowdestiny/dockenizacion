<?php
namespace EuroMillions\controllers;
use EuroMillions\entities\Language;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\services\LanguageService;
use Doctrine\ORM\EntityManager;
use EuroMillions\services\LotteriesDataService;
use Phalcon\Di;
use Phalcon\Mvc\View;

/**
 * Class PublicSiteControllerBase
 * @package EuroMillions\controllers
 * @property EntityManager $entityManager
 * @property LanguageService $language
 */
class PublicSiteControllerBase extends ControllerBase
{
    /** @var LotteriesDataService */
    protected $lotteriesDataService;

    public function initialize(LotteriesDataService $lotteriesDataService = null)
    {
        parent::initialize();
        $this->lotteriesDataService = $lotteriesDataService ? $lotteriesDataService : $this->domainServiceFactory->getLotteriesDataService();
    }

    public function afterExecuteRoute()
    {
        $this->setActiveLanguages();
        $this->setCommonTemplateVariables();
    }

    private function setActiveLanguages()
    {
        $languages = $this->language->activeLanguages();
        $view_params = [];
        /** @var Language $language */
        foreach ($languages as $language) {
            $view_params[] = $language->toValueObject();
        }
        $this->view->setVars(['languages' => $view_params]);
    }

    private function setCommonTemplateVariables()
    {
        $this->view->setVar('currency_symbol_first', true); //EMTD depending on language selected
        $jackpot = $this->lotteriesDataService->getNextJackpot('EuroMillions');
        $this->view->setVar('jackpot_value', $jackpot->getAmount()/100);
    }

}
