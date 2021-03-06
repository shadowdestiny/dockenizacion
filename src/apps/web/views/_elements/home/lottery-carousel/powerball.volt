<div class="item lottery-carousel lottery-carousel--powerball">

    <div class="top-block">
        <div class="title">
            <span class="resizeme">{{ language.translate("carousel_pow_name") }}</span>
        </div>
        <div class="lottery--value">
            {#<span class="resizeme"> {{ jackpot_powerball }}{% if milliards_euromillions %}B {% elseif trillions_euromillions %}T {% else %}M {% endif %}</span>#}
            <span class="resizeme" style="">{{  jackpot_value }}</span>
        </div>
    </div>

    <div class="bottom-block">

        <div class="lottery-carousel--clock"></div>

        <div class="lottery--countdown countdownpower" style="">
            <div class="daypower unit" style="">
                <span class="val" style=""><b>%-d</b>{% if show_p_days == '1' %}D{% else %}D{% endif %}</span>
            </div>
            <div class="dotspower"></div>
            <div class="hourpower unit" style="">
                <span class="val" style=""><b>%-H</b>H</span>
            </div>
            <div class="dotspower" style="">:</div>
            <div class="minutepower unit" style="">
                <span class="val" style=""><b>%-M</b>M</span>
            </div>
            {#<div class="resizeme">3<span>D</span> 22<span>H</span>:45<span>M</span></div>#}
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
