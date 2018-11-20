{#
Use next classes please for different lottery:
lottery-results--euromillions
lottery-results--megamillions
lottery-results--powerball
lottery-results--christmas
#}

<div class="lottery-results--carousel--section" style="">

    <div class="wrapper">

        <h2>
            Lottery results
        </h2>

        <div class="lottery-results--carousel" style="">

            {% include "_elements/home/lottery-results-carousel/euromillions.volt" %}
            {% include "_elements/home/lottery-results-carousel/powerball.volt" %}
            {% include "_elements/home/lottery-results-carousel/megamillions.volt" %}
            {% include "_elements/home/lottery-results-carousel/christmas.volt" %}


</div>

    </div>

</div>
