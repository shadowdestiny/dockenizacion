<li class="li-play{% if activeNav.myClass == 'play' %} active{% endif %}">
    <a href="/{{ language.translate("link_euromillions_play") }}">
        <span class="txt">Play Euro Millions</span>
    </a>
</li>
<li class="li-numbers{% if activeNav.myClass == 'numbers' %} active{% endif %}">
    <a href="/{{ language.translate('link_euromillions_results') }}">
        <span class="txt">Euro millions results</span>
    </a>

    <div class="submenu">
        <ul>
            <li>
                <a href="#">
                    euro millions results
                </a>
            </li>
            <li>
                <a href="#">
                    power ball results
                </a>
            </li>
        </ul>
    </div>

</li>

<li class="li-help{% if activeNav.myClass == 'help' %} active{% endif %}">
    <a href="/{{ language.translate("link_euromillions_help") }}">
        <span class="txt">How to play</span>
    </a>
</li>
