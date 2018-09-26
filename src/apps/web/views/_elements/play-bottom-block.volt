{# PAGES: Euromillions Play #}

<div class="box-bottom--accordion">
    <div class="block--text--accordion">
        <h2>
            {{ language.translate("play_firstH2") }}
        </h2>
        <p>
            {{ language.translate("play_text1") }}
        </p>
    </div>
    <div class="block--text--accordion">
        <h2>
            {{ language.translate("play_secondH2") }}
        </h2>
        <p>
            {{ language.translate("play_text2") }}
        </p>
    </div>
</div>

<div class="box-bottom play-bottom-block">

	{% if mobile != 1 %}
		<h1>
			{{ language.translate("play_h1") }}
		</h1>
	{% endif %}
    <div class="play-bottom-block--img">
        <img src="/w/img/play/desktop/play-bottom-banner.png"/>
    </div>
    <div class="play-bottom-block--center">
        <h2>
            {{ language.translate("play_firstH2") }}
        </h2>

        <p>
            {{ language.translate("play_text1") }}
        </p>
    </div>
    <div class="play-bottom-block--right">
        <h2>
            {{ language.translate("play_secondH2") }}
        </h2>
        <p>
            {{ language.translate("play_text2") }}

        </p>

    </div>
</div>