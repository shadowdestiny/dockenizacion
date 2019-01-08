<li class="li-play{% if activeNav.myClass == 'play' %} active{% endif %}">
    <a href="/{{ language.translate("link_euromillions_play") }}">
        <span class="txt">{{ language.translate("playeuromillions") }}</span>
    </a>
</li>
<li class="li-play-powerball{% if activeNav.myClass == 'powerball' %} active{% endif %}">
    <a href="/{{ language.translate("link_powerball_play") }}">
        <span class="txt">{{ language.translate("playpowerball") }}</span>
    </a>
</li>
<li class="li-play-megamillions{% if activeNav.myClass == 'megamillions' %} active{% endif %}">
    <a href="/{{ language.translate("link_megamillions_play") }}">
        <span class="txt">{{ language.translate("playmegamillions") }}</span>
    </a>
</li>
<li class="li-christmas{% if activeNav.myClass == 'christmas' %} active{% endif %}">
    <a href="/{{ language.translate("link_christmas_play") }}">
        <span class="txt">{{ language.translate("playchris_sub") }}</span>
    </a>
</li>
<li class="li-numbers{% if activeNav.myClass == 'numbers' %} active{% endif %}">
    <a href="#">
        <span class="txt">{{ language.translate("results_dropdown") }}</span>
    </a>

    <div class="submenu">
        <ul>
            <li>
                <a href="/{{ language.translate('link_euromillions_results') }}">
                    {{ language.translate("results_em_sub") }}
                </a>
            </li>
            <li>
                <a href="/{{ language.translate('link_powerball_results') }}">
                    {{ language.translate("results_pow_sub") }}
                </a>
            </li>
            <li>
                <a href="/{{ language.translate('link_megam_results') }}">
                    {{ language.translate("results_megam_sub") }}
                </a>
             </li>
            <li>
                <a href="/{{ language.translate('link_christmas_results') }}">
                    {{ language.translate("results_chris_sub") }}
                </a>
            </li>
            <li>
                <a href="/{{ language.translate('link_christmas_results') }}">
                    EUROJACKPOT RESULTS
                </a>
            </li>
            <li>
                <a href="/{{ language.translate('link_eurojackpot_draw_history') }}">
                    EUROJACKPOT HISTORY
                </a>
            </li>
        </ul>
    </div>

</li>

<li class="li-plays{% if activeNav.myClass == 'plays' %} active{% endif %}">
    <a href="/{{ language.translate("link_euromillions_faq") }}">
        <span class="txt">{{ language.translate("help_sub") }}</span>
    </a>
</li>

<li class="li-blog{% if activeNav.myClass == 'blog' %} active{% endif %}">
    <a href="/{{ language.translate("link_blogindex") }}">
        <span class="txt">{{ language.translate("blogindex") }}</span>
    </a>
</li>
