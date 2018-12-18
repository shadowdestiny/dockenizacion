<div class="item lottery-carousel lottery-carousel--christmas">
    <div class="top-block">
        <div class="title">
            <span class="resizeme" style="">Christmas Lottery</span>
        </div>
        <div class="lottery--value">
            {#<span class="resizeme" style="">{{ jackpot_christmas }}{% if milliards_christmas %}B {% elseif trillions_christmas %}T {% else %}M {% endif %} </span>#}
            <span class="resizeme" style="">€2.3B</span>
        </div>
    </div>
    <div class="bottom-block">
        <div class="lottery-carousel--clock"></div>

        <div class="christmasdate" style="">
            <div class="lottery--countdown daypower unit" style="">
                <span class="val" style="">22-12-2018</span>
            </div>
        </div>

        <div class="btn-block">
            <a href="/{{ language.translate("link_christmas_play") }}"
               class="lottery-carousel--btn ui-link" style="">
                            <span class="resizeme" style="">
                                 {{ language.translate("banner1_btn") }}
                            </span>
            </a>
        </div>

    </div>
</div>