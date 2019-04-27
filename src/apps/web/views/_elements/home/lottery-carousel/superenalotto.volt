<div class="item lottery-carousel lottery-carousel--superenalotto">

    <div class="top-block">
        <div class="title">
            <span class="resizeme">{{ language.translate("SuperEnalotto") }}</span>
        </div>
        <div class="lottery--value">
            <span class="resizeme" style="">{{ jackpot_value }}</span>
        </div>
    </div>
    <div class="bottom-block">
        <div class="lottery-carousel--clock"></div>

        <div class="lottery--countdown countdownsuper" style="">
            <div class="resizeme">
                <div class="daysuper unit" style="">
                    <span class="val" style=""><b>%-d</b>{% if show_s_days == '1' %}D{% else %}D{% endif %}</span>
                </div>
                <div class="dotssuper"></div>
                <div class="hoursuper unit" style="">
                    <span class="val" style=""><b>%-H</b>H</span>
                </div>
                <div class="dotssuper" style="">:</div>
                <div class="minutesuper unit" style="">
                    <span class="val" style=""><b>%-M</b>M</span>
                </div>
            </div>
        </div>

        <div class="btn-block">
            <a href="/{{ language.translate("link_superenalotto_play") }}"
               class="lottery-carousel--btn" style="">
                            <span class="resizeme"  style="">
                                {{ language.translate("banner1_btn") }}
                            </span>
            </a>
        </div>

    </div>
</div>
