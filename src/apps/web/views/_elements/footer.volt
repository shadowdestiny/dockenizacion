<footer data-role="footer" class="main-foot">
    <div class="wrapper">
        <div class="cols box-links">
            <div class="col20per">
                <strong>{{ language.translate('column1_head') }}</strong>
                <ul>
                    <li><a href="/{{ language.translate("link_euromillions_play") }}">{{ language.translate('column1_first') }}</a></li>
                    {# Future links
                                        <li><a href="javascript:void(0);">EuroJackpot</a></li>
                                        <li><a href="javascript:void(0);">MegaMillions</a></li>
                                        <li><a href="javascript:void(0);">PowerBall</a></li>
                    #}
                </ul>
            </div>
            <div class="col20per">
                <strong>{{ language.translate('column2_head') }}</strong>
                <ul>
                    <li><a href="/{{ language.translate('link_euromillions_results') }}">{{ language.translate('column2_first') }}</a></li>
                    <li>
                        <a href="/{{ language.translate('link_euromillions_draw_history') }}">{{ language.translate('column2_second') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col20per">
                <strong>{{ language.translate('column3_head') }}</strong>
                <ul>
                    {% if user_logged %}
                        <li><a href="/profile/tickets/games">{{ language.translate('column3Log_first') }}</a></li>
                        <li><a href="/account/wallet">{{ language.translate('column3Log_second') }}</a></li>
                        <li><a href="/account/wallet">{{ language.translate('column3Log_third') }}</a></li>
                        <li><a href="/account/wallet">{{ language.translate('column3Log_fourth') }}</a></li>
                        <li><a href="/logout">{{ language.translate("column3Log_fifth") }}</a></li>
                    {% else %}
                        <li><a href="/{{ language.translate("link_signin") }}">{{ language.translate('column3NoLog_first') }}</a></li>
                        <li><a href="/{{ language.translate("link_signup") }}">{{ language.translate('column3NoLog_second') }}</a></li>
                    {% endif %}
                </ul>
            </div>
            <div class="col20per">
                <strong>{{ language.translate('column4_head') }}</strong>
                <ul>
                    <li><a href="/{{ language.translate("link_euromillions_help") }}">{{ language.translate('column4_first') }}</a></li>
                    <li><a href="/{{ language.translate("link_euromillions_faq") }}">{{ language.translate('column4_second') }}</a></li>
                    <li><a href="/{{ language.translate("link_contact") }}/">{{ language.translate('column4_third') }}</a></li>
                </ul>
            </div>
            <div class="col20per">
                <strong>{{ language.translate('column5_head') }}</strong>
                <ul>
                    <li><a href="/{{ language.translate("link_legal_about") }}" rel="nofollow">{{ language.translate('column5_first') }}</a></li>
                    <li><a href="/{{ language.translate("link_legal_index") }}">{{ language.translate('column5_second') }}</a></li>
                    <li><a href="/{{ language.translate("link_legal_privacy") }}" rel="nofollow">{{ language.translate('column5_third') }}</a></li>
                    <li><a href="/{{ language.translate("link_legal_cookies") }}" rel="nofollow">{{ language.translate('column5_fourth') }}</a></li>


                    {# Future links
                                        <!--<li><a href="javascript:void(0);">Affiliate Program</a></li>-->
                    #}
                </ul>
            </div>
            <div class="col20per">
                <strong>{{ language.translate('langcolumn_head') }}</strong>
                <ul>
                    <li><a href="https://euromillions.com">{{ language.translate('langcolumn_en') }}</a></li>
                    <li><a href="https://euromillions.com/ru">{{ language.translate('langcolumn_ru') }}</a></li>
                    <li><a href="https://euromillions.com/es">{{ language.translate('langcolumn_es') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <aside class="logo-social cl">
        <div class="wrapper">
            <div class="social">
                <ul>
                    <li class="fb"><a href="https://www.facebook.com/Euromillionscom-204411286236724/">
                            <svg class="ico v-facebook">
                                <use xlink:href="/w/svg/icon.svg#v-facebook"></use>
                            </svg>
                            <span class="txt">{{ language.translate('Facebook') }}</span></a></li>
                    <li class="gp"><a href="https://plus.google.com/+Euromillionscom">
                            <svg class="ico v-google-plus">
                                <use xlink:href="/w/svg/icon.svg#v-google-plus"></use>
                            </svg>
                            <span class="txt">{{ language.translate('Google +') }}</span></a></li>
                    <li class="tw"><a href="https://twitter.com/_lotteries">
                            <svg class="ico v-twitter">
                                <use xlink:href="/w/svg/icon.svg#v-twitter"></use>
                            </svg>
                            <span class="txt">{{ language.translate('Twitter') }}</span></a></li>
                </ul>
            </div>
            {% include "_elements/logo.volt" %}
        </div>
    </aside>
    <div class="info cl">
        <div class="wrapper">
            <div class="cols">
                <div class="col5 txt">
                    {{ language.translate('license') }}
                    <br><br>
                    {{ language.translate('copyright') }}
                </div>
                <div class="col8 box-partner">
                    <ul class="no-li inline">
                        <li><a href="http://www.visaeurope.com/">
                                <svg class="v-visa vector">
                                    <use xlink:href="/w/svg/icon.svg#visa"/>
                                </svg>
                            </a></li>
                        <li><a href="http://www.mastercard.com/eur/">
                                <svg class="v-mastercard vector">
                                    <use xlink:href="/w/svg/icon.svg#mastercard"/>
                                </svg>
                            </a></li>
                        <li><a href="http://www.gambleaware.co.uk/">
                                <svg class="v-gambleaware vector">
                                    <use xlink:href="/w/svg/icon.svg#gambleaware"/>
                                </svg>
                            </a></li>
                        <li><a href="https://ssl.comodo.com/"><img src="/w/svg/comodo.png"/> </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="media"></div> {# Used to check the size of the document to determin what size it is with JS #}
