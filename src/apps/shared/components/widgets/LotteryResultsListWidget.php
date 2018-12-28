<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 29/11/18
 * Time: 08:52 PM
 */

namespace EuroMillions\shared\components\widgets;

use Phalcon\Di;
use Phalcon\Mvc\View\Simple as ViewSimple;
use Phalcon\Mvc\ViewInterface;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;

class LotteryResultsListWidget extends \Phalcon\Mvc\User\Component
{
    protected $lotteryResults;
    protected $lotteryName;
    protected $translationAdapter;

    public function __construct($lotteryName)
    {
        $this->lotteryName = $lotteryName;
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $this->translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));
        $lotteryService = $di->get('domainServiceFactory')->getLotteryService();
        $this->lotteryResults=$lotteryService->getLastFiveResults($this->lotteryName);
    }

    public function render()
    {
        try {
            $this->getView();
            return $this->getView()->render('_elements/home/lottery-results-carousel/lottery', ['lotteryResults' => $this->lotteryResults, 'lotteryClass' => $this->prepareClass(), 'lotteryName' => $this->lotteryName , 'checkMoreResults' => $this->prepareLastResults(), 'day' => $this->prepareWeekDays(), 'link' => $this->prepareLinks()]);
        } catch (\Exception $exc) {
        }
    }

    /**
     * Gets the view service
     *
     * @return ViewSimple|ViewInterface
     */
    public function getView()
    {
        $defaultViewsDir = $this->getDI()->get('view')->getViewsDir();
        $this->view = new ViewSimple();
        $this->view->setViewsDir($defaultViewsDir);
        return $this->view;
    }

    private function prepareClass()
    {
        switch($this->lotteryName)
        {
            case 'EuroMillions':
                return 'lottery-result--euromillions';
            case 'MegaMillions':
                return 'lottery-result--megamillions';
            case 'PowerBall':
                return 'lottery-result--powerball';
            case 'Christmas'   :
                return 'lottery-result--christmas';
            case 'EuroJackpot'   :
                return 'lottery-result--christmas';
        }
    }

    private function prepareLastResults()
    {
        return $this->translationAdapter->query('morePastResults_btn');
    }

    private function prepareWeekDays()
    {
        return ['Monday' => $this->translationAdapter->query('Monday'), 'Tuesday' => $this->translationAdapter->query('Tuesday'), 'Wednesday' => $this->translationAdapter->query('Wednesday'), 'Thursday' => $this->translationAdapter->query('Thursday'), 'Friday' => $this->translationAdapter->query('Friday'), 'Saturday' => $this->translationAdapter->query('Saturday'), 'Sunday' => $this->translationAdapter->query('Sunday')];
    }

    private function prepareLinks()
    {
        return $this->translationAdapter->query('link_'.($this->lotteryName=='MegaMillions'?'megam':mb_strtolower($this->lotteryName)).'_draw_history');
    }
}