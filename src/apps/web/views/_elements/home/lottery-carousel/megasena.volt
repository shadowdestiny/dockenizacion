<div class="item lottery-carousel lottery-carousel--powerball">
    <div class="top-block">
        <div class="title">
            <span class="resizeme" style="">Megasena</span>
        </div>
        <div class="lottery--value">
            <span class="resizeme" style="">{{ jackpot_value }}</span>
        </div>
    </div>
    <div class="bottom-block">
        <div class="lottery-carousel--clock"></div>

        <div class="lottery--countdown countdownsena" style="">
            <div class="resizeme">
                <div class="daysena unit" style="">
                    <span class="val" style=""><b>%-d</b>{% if show_s_days == '1' %}D{% else %}D{% endif %}</span>
                </div>
                <div class="dotssena"></div>
                <div class="hoursena unit" style="">
                    <span class="val" style=""><b>%-H</b>H</span>
                </div>
                <div class="dotssena" style="">:</div>
                <div class="minutesena unit" style="">
                    <span class="val" style=""><b>%-M</b>M</span>
                </div>
            </div>
            {#<div class="resizeme">3<span>D</span> 22<span>H</span>:45<span>M</span></div>#}
        </div>

        <div class="btn-block">
            <a href="/{{ language.translate("link_megasena_play") }}"
               class="lottery-carousel--btn" style="">
                            <span class="resizeme"  style="">
                                Play now
                            </span>
            </a>
        </div>

    </div>
</div>
