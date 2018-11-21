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

            {% include "_elements/home/lottery-carousel/euromillions.volt" %}
            {% include "_elements/home/lottery-carousel/powerball.volt" %}
            {% include "_elements/home/lottery-carousel/megamillions.volt" %}
            {% include "_elements/home/lottery-carousel/christmas.volt" %}




        </div>

    </div>

</div>
