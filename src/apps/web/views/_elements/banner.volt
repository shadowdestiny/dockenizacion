<div class="top-banner--section">

    <div class="top-banner--banner">
        <div class="wrapper">

            {#TODO : Add real variables here#}
            {#<div class="top-banner--head">{{ language.translate("banner1_head") }}</div>#}
            <div class="top-banner--head">
                Get that friday feeling <br>
                play for euro millions!
            </div>
            {#<div class="top-banner--subline">{{ language.translate("banner1_subline") }}</div>#}
            <div class="top-banner--subline">
                Join the crowd, become the next euro millionair!
            </div>
            {#<div class="top-banner--cost-per-lines">{{ language.translate("banner1_subbtn") }} {{ bet_price }}</div>#}
            <div class="top-banner--cost-per-lines">Only €3 per lines</div>

        </div>
    </div>

    <div class="top-banner--bottom-row">
        <div class="wrapper">

            {#TODO : Add real variables here#}

            <div class="top-banner--left desktop--only">
                Euro <br>
                Millions
            </div>

            <div class="top-banner--center">
                <div class="desktop-row--01">
                    €48
                </div>
                <div class="desktop-row--02">
                    millions
                </div>
            </div>

            <div class="top-banner--center mobile--only">

                <div class="mobile-row">

                </div>

                    {% include "_elements/for-only.volt" %}

                <div class="btn-row">
                    <a href="/{{ language.translate("link_euromillions_play") }}"
                       class="btn-theme--big">
                        {#{{ language.translate("banner1_btn") }}#}
                        bet now
                    </a>
                </div>
            </div>

            <div class="top-banner--right">

                {% include "_elements/for-only.volt" %}

                <div class="btn-row">
                    <a href="/{{ language.translate("link_euromillions_play") }}"
                       class="btn-theme--big">
                        {#{{ language.translate("banner1_btn") }}#}
                        play now
                    </a>
                </div>
            </div>

        </div>

    </div>


</div>