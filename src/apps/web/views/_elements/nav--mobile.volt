<li class="li-play{% if activeNav.myClass == 'play' %} active{% endif %}">
    <a href="/{{ language.translate("link_euromillions_play") }}">
        {% if mobile == 1 %}
            <span class="txt">Play Euro Millions</span>
        {% else %}
            <span class="txt">Play Euro Millions</span>
        {% endif %}
    </a>
</li>
<li class="li-numbers{% if activeNav.myClass == 'numbers' %} active{% endif %}">
    <a href="/{{ language.translate('link_euromillions_results') }}">
        {% if mobile == 1 %}
            <span class="txt">Euro millions results</span>
        {% else %}
            <span class="txt">Euro millions results</span>
        {% endif %}
    </a>
</li>

{#<li class="li-numbers{% if activeNav.myClass == 'numbers' %} active{% endif %}">#}
    {#<a href="/{{ language.translate('link_powerball_results') }}">#}
        {#{% if mobile == 1 %}#}
            {#<span class="txt">Powerball results</span>#}
        {#{% else %}#}
            {#<span class="txt">Powerball results</span>#}
        {#{% endif %}#}
    {#</a>#}
{#</li>#}


{% if mobile == 1 %}
    <li class="li-christmas{% if activeNav.myClass == 'christmas' %} active{% endif %}">
        <a href="/{{ language.translate('link_christmas_play') }}">
            <span class="txt">Spanish Christmas Lottery</span>
        </a>
    </li>
{% endif %}

<li class="li-help{% if activeNav.myClass == 'help' %} active{% endif %}">
    <a href="/{{ language.translate("link_euromillions_help") }}">
        <span class="txt">How to play</span>
    </a>
</li>

{% include "_elements/currencies--mobile.volt" %}
{#{% include "_elements/lang.volt" %}#}
