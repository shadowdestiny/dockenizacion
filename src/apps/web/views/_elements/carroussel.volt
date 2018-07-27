<div class="carroussel" style="margin-top: 15px;">
    <div class="top-banner-index--section">
        <div class="top-banner--bottom-row banner-carroussel">
            <div class="wrapper" id="wrapper-top-banner">
                <div class="block-carroussel-blue">
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
                <div class="block-carroussel-white">
                    &nbsp;
                </div>
                <div class="block-carroussel-red">
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
    <div style="padding-bottom: 73px;">

        <div class="grey-box-carroussel">
            <div style="margin-top:10px;"><img class="image-carroussel" src="/w/img/home/clock.png">
                <div class="countdowneuro" style="margin-top: -49px;margin-left: 80px;">
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
                <div class="btn-row" style="float:right;margin-top: -56px;margin-right: -10px;">
                    <a href="/{{ language.translate("link_euromillions_play") }}"
                       class="btn-theme--big image-carroussel" style="height: 59px;width: 212px;margin-top: 9px;margin-right: 20px;">
                        <div  style="font-size: 25px;margin-top: -19px;">
                            {{ language.translate("banner1_btn") }}
                        </div>
                    </a>
                </div>
            </div>

        </div>
        <div style="background-color: #ffffff;width: 1%; height: 100px; float:left">
        </div>

        <div class="grey-box-carroussel">
            <div style="margin-top:10px;"><img class="image-carroussel" src="/w/img/home/clock.png">
                <div class="countdownpower" style="margin-top: -49px;margin-left: 80px;">
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
                <div class="btn-row" style="float:right;margin-top: -56px;margin-right: -10px;">
                    <a href="/{{ language.translate("link_powerball_play") }}"
                       class="btn-theme--big image-carroussel" style="height: 59px;width: 212px;margin-top: 9px;margin-right: 20px;">
                        <div  style="font-size: 25px;margin-top: -19px;">
                            {{ language.translate("banner1_btn") }}
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>