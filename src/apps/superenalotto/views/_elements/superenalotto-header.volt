<header>
    <div class="left">
        <div class="top">
            {% if next_draw == 6 %}{{ language.translate('saturday') }}{% else %}{{ language.translate('thursday ') }}{% endif %}
        </div>
        <div class="bottom">
            {{ language.translate('PlaySE_jackpot') }}
        </div>
    </div>
    <div class="help-block">
        <div class="top resizeme">
            {#{{ language.translate('aboutLottery') }}#}
            {{ language.translate('PlaySE_about') }}
        </div>
        <div class="bottom">
            <a href="/{{ language.translate('link_superena_help') }}" class="a-hiw">
                {{ language.translate('PlaySE_howto') }}
            </a>
            <a href="/{{ language.translate('link_superena_results') }}" class="a-results">
                {{ language.translate('PlaySE_results') }}
            </a>
            <a href="/{{ language.translate('link_superena_faq') }}" class="a-faq">
                {{ language.translate('PlaySE_faq') }}
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
            {{ language.translate('PlaySE_instr') }}
        </div>
    </div>
</header>
