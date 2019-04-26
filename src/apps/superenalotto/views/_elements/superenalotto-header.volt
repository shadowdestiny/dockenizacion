<header>
    <div class="left">
        <div class="top">
            {% if next_draw == 6 %}{{ language.translate('saturday') }}{% else %}{{ language.translate('tuesday') }}{% endif %}
        </div>
        <div class="bottom">
            {{ language.translate('PlayMS_jackpot') }}
        </div>
    </div>
    <div class="help-block">
        <div class="top resizeme">
            {#{{ language.translate('aboutLottery') }}#}
            {{ language.translate('PlayMS_about') }}
        </div>
        <div class="bottom">
            <a href="/{{ language.translate('link_superenalotto_help') }}" class="a-hiw">
                {{ language.translate('PlayMS_howto') }}
            </a>
            <a href="/{{ language.translate('link_superenalotto_results') }}" class="a-results">
                {{ language.translate('PlayMS_results') }}
            </a>
            <a href="/{{ language.translate('link_superenalotto_faq') }}" class="a-faq">
                {{ language.translate('PlayMS_faq') }}
            </a>
        </div>
    </div>
    <div class="right">
        <div class="top resizeme">
            {{ jackpot_value_mega }}{% if milliards %}
            {{ language.translate("billion") }}
            {% elseif trillions %}
            {{ language.translate("trillion") }}
            {% else %}
            {{ language.translate("million") }}
            {% endif %}

        </div>
        <div class="bottom resizeme">
            {{ language.translate('PlayMS_instr') }}
        </div>
    </div>
</header>
