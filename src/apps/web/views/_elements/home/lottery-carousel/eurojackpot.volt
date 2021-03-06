<div class="item lottery-carousel lottery-carousel--eurojackpot">
    <div class="top-block">
        <div class="title">
            <span class="resizeme" style="">{{ language.translate("EuroJackpot") }}</span>
        </div>
        <div class="lottery--value">
            <span class="resizeme" style="">{{ jackpot_value }}</span>
        </div>
    </div>
    <div class="bottom-block">
        <div class="lottery-carousel--clock"></div>

        <div class="lottery--countdown countdowneuroj" style="">
            <div class="resizeme">
                <div class="dayeuroj unit" style="">
                    <span class="val" style=""><b>%-d</b>{% if show_ej_days == '1' %}D{% else %}D{% endif %}</span>
                </div>
                <div class="dotseuroj"></div>
                <div class="houreuroj unit" style="">
                    <span class="val" style=""><b>%-H</b>H</span>
                </div>
                <div class="dotseuroj" style="">:</div>
                <div class="minuteeuroj unit" style="">
                    <span class="val" style=""><b>%-M</b>M</span>
                </div>
            </div>
            {#<div class="resizeme">3<span>D</span> 22<span>H</span>:45<span>M</span></div>#}
        </div>

        <div class="btn-block">
            <a href="/{{ language.translate("link_eurojackpot_play") }}"
               class="lottery-carousel--btn ui-link" style="">
                            <span class="resizeme" style="">
                                 {{ language.translate("banner1_btn") }}
                            </span>
            </a>
        </div>

    </div>
</div>
