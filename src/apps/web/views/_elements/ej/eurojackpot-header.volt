<header>
    <div class="left">
        <div class="top">
            {% if next_draw == 5 %}{{ language.translate('friday') }}{% else %}{{ language.translate('tuesday') }}{% endif %}
        </div>
        <div class="bottom">
            {{ language.translate('tittle') }}
        </div>
    </div>
    <div class="help-block">
        <div class="top resizeme">
            {#{{ language.translate('aboutLottery') }}#}
            About the megamillions lottery
        </div>
        <div class="bottom">
            <a href="/{{ language.translate('link_megamillions_help') }}" class="a-hiw">
                {{ language.translate('play_howbtn') }}
            </a>
            <a href="/{{ language.translate('link_megamillions_results') }}" class="a-results">
                {{ language.translate('play_resultsbtn') }}
            </a>
            <a href="/{{ language.translate('link_megamillions_faq') }}" class="a-faq">
                {{ language.translate('play_faqbtn') }}
            </a>
        </div>
    </div>
    <div class="right">
        <div class="top resizeme">
            {#{{ jackpot_value_mega }}{% if milliards %}#}
            {#{{ language.translate("billion") }}#}
            {#{% elseif trillions %}#}
            {#{{ language.translate("trillion") }}#}
            {#{% else %}#}
            {#{{ language.translate("million") }}#}
            {#{% endif %}#}
            €28 millions
        </div>
        <div class="bottom resizeme">
            {#{{ language.translate('shortInstruction') }}#}
            Play EuroJackpot: pick 5 numbers and 2 suns
        </div>
    </div>
</header>
