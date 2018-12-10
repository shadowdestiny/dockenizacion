<div class="carroussel" style="">
    <div class="top-banner-index--section">
        <div class="top-banner--bottom-row banner-carroussel">
            <div class="wrapper wrapper-top-banner--carousel" id="wrapper-top-banner">
                <div class="block-carroussel-blue block-carroussel">
                    <div class="text-carroussel">
                        <span class="resizeme">{{ language.translate("carousel_em_name") }}</span>
                    </div>
                    <br />
                    <div class="text-sub-carroussel">
                        <div class="texter">
                            <span class="resizeme">{{ jackpot_value }}{% if milliards %}B {% elseif trillions %}T {% else %}M {% endif %}</span>
                        </div>
                        <div class="img-block">
                            <img class="image-carroussel" src="/w/img/home/starsBlue.png">
                        </div>
                    </div>
                </div>
                <div class="block-carroussel-white block-carroussel">
                    &nbsp;
                </div>
                <div class="block-carroussel-red block-carroussel">
                    <div class="text-carroussel">
                        <span class="resizeme">{{ language.translate("carousel_pow_name") }}</span>
                    </div>
                    <br />
                    <div class="text-sub-carroussel">
                        <div class="texter">
                            <span class="resizeme"> {{ jackpot_powerball }}{% if milliards %}B {% elseif trillions %}T {% else %}M {% endif %}</span>
                        </div>
                        <div class="img-block">
                            <img class="image-carroussel" src="/w/img/home/starsRed.png">
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="grey-blocks--row">

        <div class="grey-box-carroussel grey-box-carroussel--left">
            <div class="grey-box-carroussel--inner" style=""><img class="image-carroussel image-carroussel--clock" src="/w/img/home/clock.png">
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
        <div class="grey-blocks--row--separator" style="">
        </div>

        <div class="grey-box-carroussel grey-box-carroussel--right">
            <div  class="grey-box-carroussel--inner" style=""><img class="image-carroussel image-carroussel--clock" src="/w/img/home/clock.png">
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
    </div>
</div>