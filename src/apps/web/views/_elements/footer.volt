<footer data-role="footer" class="main-foot">

    <div class="footer-top">
        <div class="wrapper">
            <ul class="no-li inline">
                <li class="footer-top--eu--li">
                    <img src="/w/img/footer/top/desktop/eu.png" class="footer-top--eu"/>
                </li>
                <li class="footer-top--gerald--li">
                    <img src="/w/img/footer/top/desktop/gerald.png" class="footer-top--gerald"/>
                </li>
                <li class="footer-top--18plus--li">
                    <img src="/w/img/footer/top/desktop/18plus.png" class="footer-top--18plus"/>
                </li>
                <li class="footer-top--wheel--li">
                    <img src="/w/img/footer/top/desktop/wheel.png" class="footer-top--wheel"/>
                </li>
            </ul>
        </div>
    </div>

    <div class="wrapper">
        <div class="cols box-links">
            <div class="col col16per">
                <strong>{{ language.translate('column1_head') }}</strong>
                <ul>
                    <li>
                        <a href="/{{ language.translate('link_euromillions_play') }}">{{ language.translate('column1_second') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate('link_christmas_play') }}">{{ language.translate('column1_third') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col col16per">
                <strong>{{ language.translate('column2_head') }}</strong>
                <ul>
                    <li>
                        <a href="/{{ language.translate('link_euromillions_results') }}">{{ language.translate('column2_second') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate('link_euromillions_draw_history') }}">{{ language.translate('column2_third') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate('link_christmas_results') }}">{{ language.translate('column2_fourth') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col col20per">
                <strong>{{ language.translate('column3_head') }}</strong>
                <ul>
                    {% if user_logged %}
                        <li><a href="/profile/tickets/games">{{ language.translate('column3Log_first') }}</a></li>
                        <li><a href="/profile/transactions">{{ language.translate('column3Log_second') }}</a></li>
                        <li><a href="/account/wallet">{{ language.translate('column3Log_third') }}</a></li>
                        <li><a href="/account/wallet">{{ language.translate('column3Log_fourth') }}</a></li>
                        <li><a href="/logout">{{ language.translate("column3Log_fifth") }}</a></li>
                    {% else %}
                        <li>
                            <a href="/{{ language.translate("link_signin") }}">{{ language.translate('column3NoLog_first') }}</a>
                        </li>
                        <li>
                            <a href="/{{ language.translate("link_signup") }}">{{ language.translate('column3NoLog_second') }}</a>
                        </li>
                    {% endif %}
                </ul>
            </div>
            <div class="col col16per">
                <strong>{{ language.translate('column4_head') }}</strong>
                <ul>
                    <li>
                        <a href="/{{ language.translate("link_euromillions_faq") }}">{{ language.translate('column4_second') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate("link_contact") }}">{{ language.translate('column4_third') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col col16per">
                <strong>{{ language.translate('column5_head') }}</strong>
                <ul>
                    <li>
                        <a href="/{{ language.translate("link_legal_about") }}" rel="nofollow">{{ language.translate('column5_first') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate("link_legal_index") }}">{{ language.translate('column5_second') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate("link_legal_privacy") }}" rel="nofollow">{{ language.translate('column5_third') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate("link_legal_cookies") }}" rel="nofollow">{{ language.translate('column5_fourth') }}</a>
                    </li>


                    {# Future links
                                        <!--<li><a href="javascript:void(0);">Affiliate Program</a></li>-->
                    #}
                </ul>
            </div>
            <div class="col col16per">
                <strong>{{ language.translate('langcolumn_head') }}</strong>
                <ul>
                    <li><a href="https://euromillions.com">{{ language.translate('langcolumn_en') }}</a></li>
                    <li><a href="https://euromillions.com/ru">{{ language.translate('langcolumn_ru') }}</a></li>
                    <li><a href="https://euromillions.com/es">{{ language.translate('langcolumn_es') }}</a></li>
                    <li><a href="https://euromillions.com/it">{{ language.translate('langcolumn_it') }}</a></li>
                    <li><a href="https://euromillions.com/nl">{{ language.translate('langcolumn_nl') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="wrapper">
        {% include "_elements/cards-block.volt" %}
    </div>


    <div class="wrapper">
        <div class="copyright-text">

            <p>
                {{ language.translate('license') }}
            </p>
            <p>
                {{ language.translate('copyright') }}
            </p>

        </div>
    </div>
</footer>
<div class="media"></div> {# Used to check the size of the document to determin what size it is with JS #}
