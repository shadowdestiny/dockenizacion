<div class="item lottery-carousel lottery-carousel--euromillions">
    <div class="top-block">
        <div class="title">
            <span class="resizeme">{{ language.translate("carousel_em_name") }}</span>
        </div>
        <div class="lottery--value">
            {#<span class="resizeme">{{ jackpot_value }}{% if milliards_euromillions %}B {% elseif trillions_euromillions %}T {% else %}M {% endif %}</span>#}
            <span class="resizeme" style="">{{ jackpot_value }}</span>
        </div>
    </div>
    <div class="bottom-block">
        <div class="lottery-carousel--clock"></div>

        <div class="lottery--countdown countdowneuro" style="">
            <div class="resizeme">
                <div class="dayeuro unit" style="">
                    <span class="val" style=""><b>%-d</b>{% if show_s_days == '1' %}D{% else %}D{% endif %}</span>
                </div>
                <div class="dotseuro"></div>
                <div class="houreuro unit" style="">
                    <span class="val" style=""><b>%-H</b>H</span>
                </div>
                <div class="dotseuro" style="">:</div>
                <div class="minuteeuro unit" style="">
                    <span class="val" style=""><b>%-M</b>M</span>
                </div>
                {% if show_s_days == '1' %}
                    <div class="dotseuro" style="">:</div>
                    <div class="secondseuro unit" style="">
                        <span class="val" style="">%-SS</span>
                    </div>
                {% endif %}
            </div>
            {#<div class="resizeme">3<span>D</span> 22<span>H</span>:45<span>M</span></div>#}
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
