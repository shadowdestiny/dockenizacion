{#
Use next classes please for different jackpots:
lotteries-jackpot--bar--euromillions
lotteries-jackpot--bar--megamillions
lotteries-jackpot--bar--powerball
lotteries-jackpot--bar--christmas
#}

<div class="
lotteries-jackpot--bar
{{ main_jackpot_home.css }}
">
        <div class="wrapper" id="wrapper--lotteries-jackpot--bar">

            <div class="top-banner--top mobile--only">
                <div class="resizeme">
                    {{ main_jackpot_home.lotteryName }}
                </div>
            </div>

            <div class="top-banner--left desktop--only">
                <div class="resizeme">
                    {{ main_jackpot_home.lotteryName }}
                </div>
            </div>



            <div class="top-banner--center">
                <div class="resizeme desktop-row--01">
                    {{ jackpot_value }}
                </div>
                <div class="resizeme desktop-row--02">
                    {% if milliards %}
                        {{ language.translate("billion") }}
                    {% elseif trillions %}
                        {{ language.translate("trillion") }}
                    {% else %}
                        {{ language.translate("million") }}
                    {% endif %}
                </div>
            </div>

            <div class="top-banner--right">

                <div class="top-banner--right--clock"></div>

                <div class="top-banner--next-draw">
                    <div class="resizeme">
                        Next draw
                    </div>
                </div>
                <div class="top-banner--countdown">
                    <div class="resizeme">{% include "_elements/countdown.volt" %}</div>
                    {#<div class="resizeme">3<span>D</span>:22<span>H</span>:45<span>M</span></div>#}
                </div>

                <div class="btn-row">
                    <a href="/{{ language.translate( main_jackpot_home.link ) }}"
                       class="btn-theme--big">
                        <span class="resizeme">{{ language.translate("banner1_btn") }}</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
