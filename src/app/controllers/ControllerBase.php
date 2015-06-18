<?php
namespace EuroMillions\controllers;
use EuroMillions\entities\Language;
use EuroMillions\services\LanguageService;
use Doctrine\ORM\EntityManager;
use EuroMillions\services\LotteriesDataService;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

/**
 * Class ControllerBase
 * @package EuroMillions\controllers
 * @property EntityManager $entityManager
 * @property LanguageService $language
 */
class ControllerBase extends Controller
{
    /** @var LotteriesDataService */
    protected $lotteriesDataService;

    public function initialize()
    {
        $this->lotteriesDataService = new LotteriesDataService();
    }

    protected function noRender()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
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
        $this->view->setVar('jackpot_value', $this->lotteriesDataService->getNextJackpot('EuroMillions'));
    }

}
