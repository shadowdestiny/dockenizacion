<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 10/07/18
 * Time: 14:53
 */

namespace EuroMillions\shared\components\widgets;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
use Phalcon\Mvc\View\Simple as ViewSimple;
use Phalcon\Mvc\ViewInterface;

class JackpotAndCountDownWidget extends \Phalcon\Mvc\User\Component
{

    protected $jackpot;

    protected $lottery;

    protected $countDown;

    protected $title;

    protected $measure;

    protected $params;

    protected $link;

    protected $cssPrice;

    protected $resizeMe;

    protected $textNextDraw;

    protected $translationAdapter;

    protected $today;

    protected $lotteryName;

    protected $dateDraw;

    protected $nextDraw;

    public function __construct($jackpot,
                                Lottery $lottery,
                                $params
    )
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $this->jackpot = $jackpot;
        $this->lottery = $lottery;
        $this->translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));
        $this->title = $this->getTitle();
        $this->params = $params;
        $this->countDown = $this->params['show_s_days'];
        $this->measure = $params;
        $this->lotteryName = $this->lottery->getName();
        $this->textNextDraw = [];
        $this->dateDraw = $this->params['date_draw'];
        $this->lastLottery = $this->params['last'];

    }

    public function render()
    {
        try {
            $this->getView();
            return $this->getView()->render('_elements/jackpot-countdown-widget', ['jackpot' => $this->prepareParams()]);
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
        $this->_view = new ViewSimple();
        $this->_view->setViewsDir($this->getOptions('viewsDir', $defaultViewsDir));
        return $this->_view;
    }

    /**
     * Get options for configuring widget
     *
     * @param string|null $key
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getOptions($key = null, $default = null)
    {
        if ($key !== null) {
            return isset($this->_options[$key]) ? $this->_options[$key] : $default;
        } else {
            return $this->_options;
        }
    }

    protected function prepareParams()
    {
        $this->cssPrice = ( strlen($this->jackpot) > 4 ) ? 'price-sm' : 'price';
        $this->measure = $this->translationAdapter->query($this->getMeasure());
        $this->link = $this->lotteryLink();
        return [
            'jackpot_value' => $this->jackpot,
            'css_price' => $this->cssPrice,
            'measure' => $this->measure,
            'play_link' => $this->link,
            'title' => $this->title,
            'countDown' => $this->prepareCountDown(),
            'nextDrawButton' => $this->translationAdapter->query('nextDraw_btn'),
            'lotteryName' => $this->lotteryName,
            'date_draw' => $this->dateDraw,
            'last' => $this->lastLottery,
            'cornerCss' => $this->getCornerCss(),
            'nextDrawTest' => $this->translationAdapter->query('nextDraw_lbl')
        ];
    }

    protected function prepareCountDown()
    {
        $this->textNextDraw['nextDrawDay'] = $this->countDown == 1 ?
            "%-d" .$this->translationAdapter->query('nextDraw_oneday') :
            "%-d" .$this->translationAdapter->query('nextDraw_day');

        $this->textNextDraw['nextDrawHour'] = "%-H" . $this->translationAdapter->query('nextDraw_hr');
        $this->textNextDraw['nextDrawMin'] = "%-M" . $this->translationAdapter->query('nextDraw_min');
        $this->textNextDraw['today'] = $this->countDown;
        $this->textNextDraw['nextDrawSec'] = "%-S" . $this->translationAdapter->query('nextDraw_sec');
        return $this->textNextDraw;
    }

    protected function lotteryLink()
    {
        if($this->lottery->getName() == 'EuroMillions')
        {
            return $this->translationAdapter->query('link_euromillions_play');
        }
        if($this->lottery->getName() == 'PowerBall')
        {
            return $this->translationAdapter->query('link_powerball_play');
        }
    }

    protected function getTitle()
    {
        if($this->lottery->getName() == 'EuroMillions')
        {
            return $this->translationAdapter->query('nextDraw_Estimate');
        }
        if($this->lottery->getName() == 'PowerBall')
        {
            return $this->translationAdapter->query('powjackpot_estimate');
        }
    }

    protected function getCornerCss()
    {
        if($this->lottery->getName() == 'PowerBall')
        {
            return "corner-powerball";
        }
        if($this->lottery->getName() == 'EuroMillions')
        {
            return "corner";
        }

    }

    protected function getMeasure()
    {
        if($this->measure['milliards']) return 'billion';
        if($this->measure['trillions']) return 'trillion';
        return 'million';
    }


}