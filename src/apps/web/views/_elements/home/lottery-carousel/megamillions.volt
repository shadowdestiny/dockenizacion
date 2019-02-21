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

        <div class="lottery--countdown countdownmega" style="">
            <div class="resizeme">
                <div class="daymega unit" style="">
                    <span class="val" style=""><b>%-d</b>{% if show_s_days == '1' %}D{% else %}D{% endif %}</span>
                </div>
                <div class="dotsmega"></div>
                <div class="hourmega unit" style="">
                    <span class="val" style=""><b>%-H</b>H</span>
                </div>
                <div class="dotsmega" style="">:</div>
                <div class="minutemega unit" style="">
                    <span class="val" style=""><b>%-M</b>M</span>
                </div>
            </div>
            {#<div class="resizeme">3<span>D</span> 22<span>H</span>:45<span>M</span></div>#}
        </div>

        <div class="btn-block">
            <a href="/{{ language.translate("link_megamillions_play") }}"
               class="lottery-carousel--btn" style="">
                            <span class="resizeme"  style="">
                                Play now
                            </span>
            </a>
        </div>

    </div>
</div>
