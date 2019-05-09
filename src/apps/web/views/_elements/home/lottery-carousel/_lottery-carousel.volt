{#
Use next classes please for different lottery:
lottery-carousel--euromillions
lottery-carousel--megamillions
lottery-carousel--powerball
lottery-carousel--christmas
#}

<div class="lotteries--carousel--section" style="">

    <div class="wrapper">

        <h2>
            PLAY EUROMILLIONS,<br>
            EUROPE'S BEST LOTTO
        </h2>

        <div class="lotteries--carousel owl-carousel owl-theme" style="">

            {% for slide in slide_jackpot_include %}
               {% if slide.lotteryName == 'EuroMillions' %}
                    {% set jackpot_value = slide.jackpot %}
                    {% set draw_date = slide.drawDateFormat %}
                    {% include "_elements/home/lottery-carousel/euromillions.volt" %}
                {%  endif %}
                {% if slide.lotteryName == 'PowerBall' %}
                    {% set jackpot_value = slide.jackpot %}
                    {% set draw_date = slide.drawDateFormat %}
                    {% include "_elements/home/lottery-carousel/powerball.volt" %}
                {%  endif %}
                {% if slide.lotteryName == 'MegaMillions' %}
                    {% set jackpot_value = slide.jackpot %}
                    {% set draw_date = slide.drawDateFormat %}
                    {% include "_elements/home/lottery-carousel/megamillions.volt" %}
                {%  endif %}
                {% if slide.lotteryName == 'EuroJackpot' %}
                    {% set jackpot_value = slide.jackpot %}
                    {% set draw_date = slide.drawDateFormat %}
                    {% include "_elements/home/lottery-carousel/eurojackpot.volt" %}
                {%  endif %}
                {% if slide.lotteryName == 'MegaSena' %}
                    {% set jackpot_value = slide.jackpot %}
                    {% set draw_date = slide.drawDateFormat %}
                    {% include "_elements/home/lottery-carousel/megasena.volt" %}
                {%  endif %}
                {% if slide.lotteryName == 'SuperEnalotto' %}
                    {% set jackpot_value = slide.jackpot %}
                    {% set draw_date = slide.drawDateFormat %}
                    {% include "_elements/home/lottery-carousel/superenalotto.volt" %}
                {%  endif %}

            {%  endfor %}
        </div>

    </div>

</div>
