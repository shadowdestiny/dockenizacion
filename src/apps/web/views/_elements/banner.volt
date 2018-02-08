<div class="top-banner--section">

    <div class="top-banner--banner">
        <div class="wrapper">

            <h1 class="top-banner--head">
                {{ language.translate("banner1_h1") }}
            </h1>
            <div class="top-banner--subline">
                {{ language.translate("banner1_subline") }}
            </div>
            <div class="top-banner--cost-per-lines">
                {{ language.translate("banner1_subbtn") }} {{ bet_price }}
            </div>
        </div>
    </div>

    <div class="top-banner--bottom-row">
        <div class="wrapper">

            <div class="top-banner--left desktop--only">
                {{ language.translate("banner1_subhead") }}
            </div>

            <div class="top-banner--center desktop--only">
                <div class="desktop-row--01{% if jackpot_value|length > 4 %}-sm{% endif %}">
                    {{ jackpot_value }}
                </div>
                <div class="desktop-row--02">
                    {{ language.translate("million") }}
                </div>
            </div>

            <div class="top-banner--center mobile--only">

                <div class="star--01"></div>
                <div class="star--02"></div>
                <div class="star--03"></div>

                <div class="mobile-row">
                    {{ jackpot_value }}{{ language.translate("million") }}
                </div>
                <div class="top-banner--for-only">
                    {% include "_elements/countdown--home.volt" %}
                </div>

                <div class="btn-row">
                    <a href="/{{ language.translate("link_euromillions_play") }}"
                       class="btn-theme--big">
                        {{ language.translate("banner1_btn") }}
                    </a>
                </div>
            </div>

            <div class="top-banner--right desktop--only">

                <div class="top-banner--for-only">
                    {% include "_elements/countdown.volt" %}
                </div>

                <div class="btn-row">
                    <a href="/{{ language.translate("link_euromillions_play") }}"
                       class="btn-theme--big">
                        {{ language.translate("banner1_btn") }}
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>