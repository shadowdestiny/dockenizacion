<div class="item lottery-carousel lottery-carousel--megamillions">
    <div class="top-block">
        <div class="title">
            <span class="resizeme" style="">Megamillions</span>
        </div>
        <div class="lottery--value">
            <span class="resizeme" style="">{{ jackpot_value }}</span>
        </div>
    </div>
    <div class="bottom-block">
        <div class="lottery-carousel--clock"></div>

        <div class="lottery--countdown countdownpower" style="">
            {#<div class="daypower unit" style="">#}
                {#<span class="val" style="">%-d{% if show_p_days == '1' %}D{% else %}D{% endif %}</span>#}
            {#</div>#}
            {#<div class="dotspower"></div>#}
            {#<div class="hourpower unit" style="">#}
                {#<span class="val" style="">%-HH</span>#}
            {#</div>#}
            {#<div class="dotspower" style="">:</div>#}
            {#<div class="minutepower unit" style="">#}
                {#<span class="val" style="">%-MM</span>#}
            {#</div>#}
            {#{% if show_p_days == '1' %}#}
                {#<div class="dotspower" style="">:</div>#}
                {#<div class="secondspower unit" style="">#}
                    {#<span class="val" style="">%-SS</span>#}
                {#</div>#}
            {#{% endif %}#}
            <div class="resizeme">3<span>D</span> 22<span>H</span>:45<span>M</span></div>
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