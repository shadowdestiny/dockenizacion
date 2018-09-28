{# PAGES: Powerball Play #}

<div class="box-bottom--accordion--powerplay">
    <div class="block--text--accordion">
        <h2>
            {{ language.translate("play_pow_firstH2") }}
        </h2>
        <p>
            {{ language.translate("play_pow_text1") }}
        </p>
    </div>
    <div class="block--text--accordion">
        <h2>
            {{ language.translate("play_pow_secondH2") }}
        </h2>
        <p>
            {{ language.translate("play_pow_text2") }}
        </p>
    </div>
</div>

<div class="box-bottom play-bottom-block--powerplay">

	{% if mobile != 1 %}
		<h1>
			{{ language.translate("play_pow_h1") }}
		</h1>
	{% endif %}
    <div class="play-bottom-block--img">
        <img src="https://images.euromillions.com/imgs/play-bottom-powerball.png"/>
    </div>
    <div class="play-bottom-block--center">
        <h2>
            {{ language.translate("play_pow_firstH2") }}
        </h2>

        <p>
            {{ language.translate("play_pow_text1") }}
        </p>
    </div>
    <div class="play-bottom-block--right">
        <h2>
            {{ language.translate("play_pow_secondH2") }}
        </h2>

        <p>
            {{ language.translate("play_pow_text2") }}
        </p>

    </div>
</div>

