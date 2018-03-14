<div class="top-banner-index--section">

    <div class="top-banner--banner">
        <div class="wrapper">

            <h3 class="top-banner--head">
                {% if mobile == 1 %}
                    {{ language.translate("home_mobile_h1") }}
                {% else %}
                    {{ language.translate("banner1_h1") }}
                {% endif %}
            </h3>
            <div class="top-banner--subline">
                {{ language.translate("banner1_subline") }}
            </div>
            <div class="top-banner--cost-per-lines">
                {{ language.translate("banner1_subbtn") }} {{ bet_price }}
            </div>
        </div>
    </div>

    <div class="top-banner--bottom-row">
        <div class="wrapper" id="wrapper-top-banner">

            <div class="top-banner--left desktop--only">
                <div class="resizeme">
                {{ language.translate("banner1_subhead") }}
                </div>
            </div>

            <div class="top-banner--center desktop--only">
                {#<div class="resizeme desktop-row--01{% if jackpot_value|length > 4 %}-sm{% endif %}">#}
                    {#{{ jackpot_value }}#}
                {#</div>#}
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

            <div class="top-banner--center mobile--only">

                <div class="star--01"></div>
                <div class="star--02"></div>
                <div class="star--03"></div>

                <div class="mobile-row">
                    {{ jackpot_value }}{{ language.translate("million") }}
                </div>
                <div class="top-banner--for-only">
                    <div class="resizeme">
                    {% include "_elements/countdown--home.volt" %}
                    </div>
                </div>

                    <a href="/{{ language.translate("link_euromillions_play") }}"
                       class="btn-theme--big">
                        <span class="resizeme">
                        {{ language.translate("banner1_btn") }}
                            </span>
                    </a>

            </div>

            <div class="top-banner--right desktop--only">

                <div class="top-banner--for-only">
                    <div class="resizeme">
                    {% include "_elements/countdown.volt" %}
                    </div>
                </div>

                <div class="btn-row">
                    <a href="/{{ language.translate("link_euromillions_play") }}"
                       class="btn-theme--big">
                        <span class="resizeme">
                        {{ language.translate("banner1_btn") }}
                            </span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>