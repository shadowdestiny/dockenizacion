<header>
    <div class="left">
        <div class="top">
            {{ language.translate('friday') }}
        </div>
        <div class="bottom">
            {{ language.translate('PlayEJ_jackpot') }}
        </div>
    </div>
    <div class="help-block">
        <div class="top resizeme">
            {#{{ "About the eurojackpot lottery" }}#}
            {{ language.translate('PlayEJ_about') }}
        </div>
        <div class="bottom">
            <a href="/{{ language.translate('link_eurojackpot_help') }}" class="a-hiw">
                {{ language.translate('PlayEJ_howto') }}
            </a>
            <a href="/{{ language.translate('link_eurojackpot_results') }}" class="a-results">
                {{ language.translate('PlayEJ_results') }}
            </a>
            <a href="/{{ language.translate('link_eurojackpot_faq') }}" class="a-faq">
                {{ language.translate('PlayEJ_faq') }}
            </a>
        </div>
    </div>
    <div class="right">
        <div class="top resizeme">
            {{ jackpot_value_eurojackpot }}
            {% if milliards %}
                {{ language.translate("billion") }}
            {% elseif trillions %}
                {{ language.translate("trillion") }}
            {% else %}
                {{ language.translate("million") }}
            {% endif %}
        </div>
        <div class="bottom resizeme">
            {#{{ "Play EuroJackpot: pick 5 numbers and 2 suns" }}#}
            {{ language.translate('PlayEJ_instr') }}
        </div>
    </div>
</header>
