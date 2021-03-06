<div class="carroussel" style="">
    <div class="top-banner-index--section">
        <div class="top-banner--bottom-row banner-carroussel">


            <!--            We use different CSS classes for blocks showing and width :-->
            <!--            mobile-2x : show 2 blocks on mobile devices-->
            <!--            desktop-2x : show 2 blocks hide on desktop devices-->
            <!--            mobile-3x : show 3 blocks on mobile devices-->
            <!--            desktop-3x : show 3 blocks hide on desktop devices-->
            <!--            hide-mobile : hide on mobile devices-->
            <!--            hide-desktop : hide on desktop devices-->


            <div class="wrapper wrapper-top-banner--carousel mobile-2x desktop-3x" id="wrapper-top-banner">

                <div class="block-carroussel-euromillions block-carroussel hide-mobile">
                    <a href="/{{ language.translate("link_euromillions_play") }}"
                       class="btn-theme--big--top image-carroussel" style="">
                    </a>
                    <div class="text-carroussel">
                        <span class="resizeme">{{ language.translate("carousel_em_name") }}</span>
                    </div>
                    <br />
                    <div class="text-sub-carroussel">
                        <div class="texter">
                            <span class="resizeme">{{ jackpot_value }}{% if milliards_euromillions %}B {% elseif trillions_euromillions %}T {% else %}M {% endif %}</span>
                        </div>
                        <div class="img-block">
                            <img class="image-carroussel" src="https://images.euromillions.com/imgs/starsBlue.png">
                        </div>
                    </div>
                </div>

                <div class="block-carroussel-powerball block-carroussel">
                    <a href="/{{ language.translate("link_powerball_play") }}"
                       class="btn-theme--big--top image-carroussel" style="">
                    </a>
                    <div class="text-carroussel">
                        <span class="resizeme">{{ language.translate("carousel_pow_name") }}</span>
                    </div>
                    <br />
                    <div class="text-sub-carroussel">
                        <div class="texter">
                            <span class="resizeme"> {{ jackpot_powerball }}{% if milliards_euromillions %}B {% elseif trillions_euromillions %}T {% else %}M {% endif %}</span>
                        </div>
                        <div class="img-block">
                            <img class="image-carroussel" src="https://images.euromillions.com/imgs/starsRed.png">
                        </div>

                    </div>
                </div>

                <div class="block-carroussel-christmas block-carroussel">
                    <a href="https://euromillions.com/christmas-lottery/play"
                       class="btn-theme--big--top image-carroussel ui-link" style="">
                    </a>
                    <div class="text-carroussel">
                        <span class="resizeme" style="">Christmas Lottery</span>
                    </div>
                    <br>
                    <div class="text-sub-carroussel">
                        <div class="texter">
                                    <span class="resizeme"
                                          style="">{{ jackpot_christmas }}{% if milliards_christmas %}B {% elseif trillions_christmas %}T {% else %}M {% endif %} </span>
                        </div>
                        <div class="img-block">
                            <img class="image-carroussel" src="https://images.euromillions.com/imgs/starsChristmas.png">
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="grey-blocks--row mobile-2x desktop-3x">

        <div class="grey-box-carroussel grey-box-carroussel--euromillions hide-mobile">
            <div class="grey-box-carroussel--inner" style=""><img class="image-carroussel image-carroussel--clock" src="https://images.euromillions.com/imgs/clock.png">
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
                <div class="btn-row" style="">
                    <a href="/{{ language.translate("link_euromillions_play") }}"
                       class="btn-theme--big image-carroussel" style="">
                        <span class="resizeme"  style="">
                            {{ language.translate("banner1_btn") }}
                        </span>
                    </a>
                </div>
            </div>

        </div>

        <div class="grey-box-carroussel grey-box-carroussel--powerball">
            <div  class="grey-box-carroussel--inner" style=""><img class="image-carroussel image-carroussel--clock" src="https://images.euromillions.com/imgs/clock.png">
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
                <div class="btn-row" style="">
                    <a href="/{{ language.translate("link_powerball_play") }}"
                       class="btn-theme--big image-carroussel" style="">
                        <span class="resizeme"  style="">
                            {{ language.translate("banner1_btn") }}
                        </span>
                    </a>
                </div>
            </div>

        </div>

        <div class="grey-box-carroussel grey-box-carroussel--christmas">
            <div class="grey-box-carroussel--inner" style=""><img class="image-carroussel image-carroussel--clock" src="https://images.euromillions.com/imgs/clock.png">
                <div class="christmasdate" style="">
                    <div class="daypower unit" style="color:black;">
                        <span class="val" style="color:black;">22.12.2018</span>
                    </div>
                </div>
                <div class="btn-row" style="">
                    <a href="/{{ language.translate("link_christmas_play") }}"
                       class="btn-theme--big image-carroussel ui-link" style="">
                        <span class="resizeme" style="">
                             {{ language.translate("banner1_btn") }}
                        </span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>