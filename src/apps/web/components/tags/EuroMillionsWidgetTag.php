<?php


namespace EuroMillions\web\components\tags;


use Phalcon\Tag;

class EuroMillionsWidgetTag extends Tag
{

    protected static $_time = '';
    protected static $_jackpot = '';
    protected static $_link = '';
    protected static $_name = '';


    public static function setTime($time) {
        self::$_time = $time;
    }

    public static function setJackpot($jackpot) {
        self::$_jackpot = $jackpot;
    }

    public static function setLink($link) {
        self::$_link = $link;
    }

    public static function setName($name) {
        self::$_name = $name;
    }

    /**
     * Get current document meta description
     *
     * @param boolean $tags
     * @return string
     */
    public static function getWidget($tags = true) {
        $total = '';
        if ($tags) {
            foreach (self::$_time as $key=>$val) {
                $total .= ' <section class="section-02">
            <div class="corner"></div>
            <div class="title">
                '. self::$_name .'
            </div>
            <div class="price">
                '. self::$_jackpot .'
            </div>
            <div class="measure">
                millions
            </div>

            <div class="timer">
                <div class="countdown">
                    <div class="day unit">
                    </div>
                    <div class="dots">:</div>
                    <div class="hour unit">
                        <span class="val">%-H {{ language.translate("nextDraw_hr") }}</span>
                    </div>
                    <div class="dots">:</div>
                    <div class="minute unit">
                        <span class="val">%-M {{ language.translate("nextDraw_min") }}</span>
                    </div>
                    {% if show_s_days == \'0\' %}
                    <div class="dots">:</div>
                    <div class="seconds unit">
                        <span class="val">%-S {{ language.translate("nextDraw_sec") }}</span>
                    </div>
                    {% endif %}
                </div>
            </div>

            <div class="btn-row">
                <a href="/'.self::$_link.'"
                   class="btn-theme--big">
                    Play now
                </a>
            </div>
        </section>';
            }
            return $total;
        }
        return self::$_documentDescription;
    }
}