{#
Use next classes please for different lottery:
lottery-carrousel--euromillions
lottery-carrousel--megamillions
lottery-carrousel--powerball
lottery-carrousel--christmas
#}

<div class="lotteries--carrousel--section" style="">

    <div class="wrapper">

        <h2>
            PLAY EUROMILLIONS, EUROPE'S BEST LOTTO
        </h2>

        <div class="lotteries--carrousel" style="">

        <div class="item lottery-carrousel lottery-carrousel--powerball">
            <div class="top-block">
                <div class="title">
                    <span class="resizeme">{{ language.translate("carousel_pow_name") }}</span>
                </div>
                <div class="value">
                    <span class="resizeme"> {{ jackpot_powerball }}{% if milliards_euromillions %}B {% elseif trillions_euromillions %}T {% else %}M {% endif %}</span>
                </div>
            </div>
            <div class="bottom-block">
                <img class="image-carroussel image-carroussel--clock" src="https://images.euromillions.com/imgs/clock.png">

                <div class="countdownpower" style="">
                    <div class="daypower unit" style="color:black;">
                        <span class="val" style="color:black;">%-d{% if show_p_days == '1' %}D{% else %}D{% endif %}</span>
                    </div>
                    <div class="dotspower"></div>
                    <div class="hourpower unit" style="color:black;">
                        <span class="val" style="color:black;">%-HH</span>
                    </div>
                    <div class="dotspower" style="color:black;">:</div>
                    <div class="minutepower unit" style="color:black;">
                        <span class="val" style="color:black;">%-MM</span>
                    </div>
                    {% if show_p_days == '1' %}
                        <div class="dotspower" style="color:black;">:</div>
                        <div class="secondspower unit" style="color:black;">
                            <span class="val" style="color:black;">%-SS</span>
                        </div>
                    {% endif %}
                </div>

                <div class="btn-block">
                    <a href="/{{ language.translate("link_powerball_play") }}"
                       class="lottery-carrousel--btn" style="">
                        <span class="resizeme"  style="">
                            {{ language.translate("banner1_btn") }}
                        </span>
                    </a>
                </div>

            </div>
        </div>
        <div class="item lottery-carrousel lottery-crarrousel--megamillions">
            <div class="top-block">
                <div class="title">
                    <span class="resizeme" style="">Megamillionsy</span>
                </div>
                <div class="value">
                    <span class="resizeme" style="">€149M</span>
                </div>
            </div>
            <div class="bottom-block">
                <img class="image-carroussel image-carroussel--clock" src="https://images.euromillions.com/imgs/clock.png">

                <div class="countdownpower" style="">
                    <div class="daypower unit" style="color:black;">
                        <span class="val" style="color:black;">%-d{% if show_p_days == '1' %}D{% else %}D{% endif %}</span>
                    </div>
                    <div class="dotspower"></div>
                    <div class="hourpower unit" style="color:black;">
                        <span class="val" style="color:black;">%-HH</span>
                    </div>
                    <div class="dotspower" style="color:black;">:</div>
                    <div class="minutepower unit" style="color:black;">
                        <span class="val" style="color:black;">%-MM</span>
                    </div>
                    {% if show_p_days == '1' %}
                        <div class="dotspower" style="color:black;">:</div>
                        <div class="secondspower unit" style="color:black;">
                            <span class="val" style="color:black;">%-SS</span>
                        </div>
                    {% endif %}
                </div>

                <div class="btn-block">
                    <a href="/{{ language.translate("link_powerball_play") }}"
                       class="lottery-carrousel--btn" style="">
                        <span class="resizeme"  style="">
                            Play now
                        </span>
                    </a>
                </div>

            </div>
        </div>
        <div class="item lottery-carrousel lottery-carrousel--christmas">
            <div class="top-block">
                <div class="title">
                    <span class="resizeme" style="">Christmas Lottery</span>
                </div>
                <div class="value">
                    <span class="resizeme" style="">{{ jackpot_christmas }}{% if milliards_christmas %}B {% elseif trillions_christmas %}T {% else %}M {% endif %} </span>
                </div>
            </div>
            <div class="bottom-block">
                <img class="image-carroussel image-carroussel--clock" src="https://images.euromillions.com/imgs/clock.png">

                <div class="christmasdate" style="">
                    <div class="daypower unit" style="color:black;">
                        <span class="val" style="color:black;">22.12.2018</span>
                    </div>
                </div>

                <div class="btn-block">
                    <a href="/{{ language.translate("link_christmas_play") }}"
                       class="lottery-carrousel--btn ui-link" style="">
                        <span class="resizeme" style="">
                             {{ language.translate("banner1_btn") }}
                        </span>
                    </a>
                </div>

            </div>
        </div>
        <div class="item lottery-carrousel lottery-carrousel--euromillions">
            <div class="top-block">
                <div class="title">
                    <span class="resizeme">{{ language.translate("carousel_em_name") }}</span>
                </div>
                <div class="value">
                    <span class="resizeme">{{ jackpot_value }}{% if milliards_euromillions %}B {% elseif trillions_euromillions %}T {% else %}M {% endif %}</span>
                </div>
            </div>
            <div class="bottom-block">
                <img class="image-carroussel image-carroussel--clock" src="https://images.euromillions.com/imgs/clock.png">

                <div class="countdowneuro" style="">
                    <div class="dayeuro unit" style="color:black;">
                        <span class="val" style="color:black;">%-d{% if show_s_days == '1' %}D{% else %}D{% endif %}</span>
                    </div>
                    <div class="dotseuro"></div>
                    <div class="houreuro unit" style="color:black;">
                        <span class="val" style="color:black;">%-HH</span>
                    </div>
                    <div class="dotseuro" style="color:black;">:</div>
                    <div class="minuteeuro unit" style="color:black;">
                        <span class="val" style="color:black;">%-MM</span>
                    </div>
                    {% if show_s_days == '1' %}
                        <div class="dotseuro" style="color:black;">:</div>
                        <div class="secondseuro unit" style="color:black;">
                            <span class="val" style="color:black;">%-SS</span>
                        </div>
                    {% endif %}
                </div>

                <div class="btn-block">
                    <a href="/{{ language.translate("link_euromillions_play") }}"
                       class="lottery-carrousel--btn" style="">
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


