{#
Use next classes please for different lottery:
lottery-carousel--euromillions
lottery-carousel--megamillions
lottery-carousel--powerball
lottery-carousel--christmas
#}

<div class="lotteries--carousel--section" style="">

    <div class="wrapper">

        <h2>
            PLAY EUROMILLIONS,<br>
            EUROPE'S BEST LOTTO
        </h2>

        <div class="lotteries--carrousel owl-carousel owl-theme" style="">

        <div class="item lottery-carousel lottery-carousel--powerball">

            <div class="top-block">
                <div class="title">
                    <span class="resizeme">{{ language.translate("carousel_pow_name") }}</span>
                </div>
                <div class="lottery--value">
                    {#<span class="resizeme"> {{ jackpot_powerball }}{% if milliards_euromillions %}B {% elseif trillions_euromillions %}T {% else %}M {% endif %}</span>#}
                    <span class="resizeme" style="">€149M</span>
                </div>
            </div>

            <div class="bottom-block">

                <div class="lottery-carousel--clock"></div>

                <div class="lottery--countdown countdownpower" style="">
                    <div class="daypower unit" style="">
                        <span class="val" style="">%-d{% if show_p_days == '1' %}D{% else %}D{% endif %}</span>
                    </div>
                    <div class="dotspower"></div>
                    <div class="hourpower unit" style="">
                        <span class="val" style="">%-HH</span>
                    </div>
                    <div class="dotspower" style="">:</div>
                    <div class="minutepower unit" style="">
                        <span class="val" style="">%-MM</span>
                    </div>
                    {% if show_p_days == '1' %}
                        <div class="dotspower" style="">:</div>
                        <div class="secondspower unit" style="">
                            <span class="val" style="">%-SS</span>
                        </div>
                    {% endif %}
                </div>

                <div class="btn-block">
                    <a href="/{{ language.translate("link_powerball_play") }}"
                       class="lottery-carousel--btn" style="">
                        <span class="resizeme"  style="">
                            {{ language.translate("banner1_btn") }}
                        </span>
                    </a>
                </div>

            </div>
        </div>
        <div class="item lottery-carousel lottery-carousel--megamillions">
            <div class="top-block">
                <div class="title">
                    <span class="resizeme" style="">Megamillions</span>
                </div>
                <div class="lottery--value">
                    <span class="resizeme" style="">€149M</span>
                </div>
            </div>
            <div class="bottom-block">
                <div class="lottery-carousel--clock"></div>

                <div class="lottery--countdown countdownpower" style="">
                    <div class="daypower unit" style="">
                        <span class="val" style="">%-d{% if show_p_days == '1' %}D{% else %}D{% endif %}</span>
                    </div>
                    <div class="dotspower"></div>
                    <div class="hourpower unit" style="">
                        <span class="val" style="">%-HH</span>
                    </div>
                    <div class="dotspower" style="">:</div>
                    <div class="minutepower unit" style="">
                        <span class="val" style="">%-MM</span>
                    </div>
                    {% if show_p_days == '1' %}
                        <div class="dotspower" style="">:</div>
                        <div class="secondspower unit" style="">
                            <span class="val" style="">%-SS</span>
                        </div>
                    {% endif %}
                </div>

                <div class="btn-block">
                    <a href="/{{ language.translate("link_powerball_play") }}"
                       class="lottery-carousel--btn" style="">
                        <span class="resizeme"  style="">
                            Play now
                        </span>
                    </a>
                </div>

            </div>
        </div>
        <div class="item lottery-carousel lottery-carousel--christmas">
            <div class="top-block">
                <div class="title">
                    <span class="resizeme" style="">Christmas Lottery</span>
                </div>
                <div class="lottery--value">
                    {#<span class="resizeme" style="">{{ jackpot_christmas }}{% if milliards_christmas %}B {% elseif trillions_christmas %}T {% else %}M {% endif %} </span>#}
                    <span class="resizeme" style="">€149M</span>
                </div>
            </div>
            <div class="bottom-block">
                <div class="lottery-carousel--clock"></div>

                <div class="christmasdate" style="">
                    <div class="lottery--countdown daypower unit" style="">
                        <span class="val" style="">22.12.2018</span>
                    </div>
                </div>

                <div class="btn-block">
                    <a href="/{{ language.translate("link_christmas_play") }}"
                       class="lottery-carousel--btn ui-link" style="">
                        <span class="resizeme" style="">
                             {{ language.translate("banner1_btn") }}
                        </span>
                    </a>
                </div>

            </div>
        </div>

        <div class="item lottery-carousel lottery-carousel--euromillions">
            <div class="top-block">
                <div class="title">
                    <span class="resizeme">{{ language.translate("carousel_em_name") }}</span>
                </div>
                <div class="lottery--value">
                    {#<span class="resizeme">{{ jackpot_value }}{% if milliards_euromillions %}B {% elseif trillions_euromillions %}T {% else %}M {% endif %}</span>#}
                    <span class="resizeme" style="">€149M</span>
                </div>
            </div>
            <div class="bottom-block">
                <div class="lottery-carousel--clock"></div>

                <div class="lottery--countdown countdowneuro" style="">
                    <div class="dayeuro unit" style="">
                        <span class="val" style="">%-d{% if show_s_days == '1' %}D{% else %}D{% endif %}</span>
                    </div>
                    <div class="dotseuro"></div>
                    <div class="houreuro unit" style="">
                        <span class="val" style="">%-HH</span>
                    </div>
                    <div class="dotseuro" style="">:</div>
                    <div class="minuteeuro unit" style="">
                        <span class="val" style="">%-MM</span>
                    </div>
                    {% if show_s_days == '1' %}
                        <div class="dotseuro" style="">:</div>
                        <div class="secondseuro unit" style="">
                            <span class="val" style="">%-SS</span>
                        </div>
                    {% endif %}
                </div>

                <div class="btn-block">
                    <a href="/{{ language.translate("link_euromillions_play") }}"
                       class="lottery-carousel--btn" style="">
                        <span class="resizeme"  style="">
                            {{ language.translate("banner1_btn") }}
                        </span>
                    </a>
                </div>

            </div>
        </div>
</div>

    </div>

</div>
