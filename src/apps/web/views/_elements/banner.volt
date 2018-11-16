<div class="top-banner-index--section">

    <div class="top-banner--banner">
        <div class="wrapper">

			{% if mobile != 1 %}
				<h1 class="top-banner--head">
					{{ language.translate("banner1_h1") }}
				</h1>
            {% endif %}
            <div class="top-banner--subline">
                {{ language.translate("banner1_subline") }}
            </div>
            <div class="top-banner--cost-per-lines">
                {{ language.translate("banner1_subbtn") }} {{ bet_price }}
            </div>
        </div>
    </div>

    {#{% include "_elements/home/banner_old.volt" %}#}

    {% include "_elements/home/banner.volt" %}

</div>