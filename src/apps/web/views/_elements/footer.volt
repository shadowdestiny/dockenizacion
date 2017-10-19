<footer data-role="footer" class="main-foot">

  

    <div class="wrapper">
        <div class="cols box-links">
            <div class="col20per">
                <strong>{{ language.translate('column1_head') }}</strong>
                <ul>
                    <li>
                        <a href="/{{ language.translate("link_euromillions_play") }}">{{ language.translate('column1_first') }}</a>
                    </li>
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
                    <li>
                        <a href="/{{ language.translate('link_euromillions_results') }}">{{ language.translate('column2_first') }}</a>
                    </li>
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
                        <li>
                            <a href="/{{ language.translate("link_signin") }}">{{ language.translate('column3NoLog_first') }}</a>
                        </li>
                        <li>
                            <a href="/{{ language.translate("link_signup") }}">{{ language.translate('column3NoLog_second') }}</a>
                        </li>
                    {% endif %}
                </ul>
            </div>
            <div class="col20per">
                <strong>{{ language.translate('column4_head') }}</strong>
                <ul>
                    <li>
                        <a href="/{{ language.translate("link_euromillions_help") }}">{{ language.translate('column4_first') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate("link_euromillions_faq") }}">{{ language.translate('column4_second') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate("link_contact") }}/">{{ language.translate('column4_third') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col20per">
                <strong>{{ language.translate('column5_head') }}</strong>
                <ul>
                    <li>
                        <a href="/{{ language.translate("link_legal_about") }}">{{ language.translate('column5_first') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate("link_legal_index") }}">{{ language.translate('column5_second') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate("link_legal_privacy") }}">{{ language.translate('column5_third') }}</a>
                    </li>
                    <li>
                        <a href="/{{ language.translate("link_legal_cookies") }}">{{ language.translate('column5_fourth') }}</a>
                    </li>


                    {# Future links
                                        <!--<li><a href="javascript:void(0);">Affiliate Program</a></li>-->
                    #}
                </ul>
            </div>
        </div>
    </div>

    {#TODO : remove this comments#}
    {#<aside class="logo-social cl">#}
        {#<div class="wrapper">#}
            {#<div class="social">#}
                {#<ul>#}
                    {#<li class="fb"><a href="https://www.facebook.com/Euromillionscom-204411286236724/">#}
                            {#<svg class="ico v-facebook">#}
                                {#<use xlink:href="/w/svg/icon.svg#v-facebook"></use>#}
                            {#</svg>#}
                            {#<span class="txt">{{ language.translate('Facebook') }}</span></a></li>#}
                    {#<li class="gp"><a href="https://plus.google.com/+Euromillionscom">#}
                            {#<svg class="ico v-google-plus">#}
                                {#<use xlink:href="/w/svg/icon.svg#v-google-plus"></use>#}
                            {#</svg>#}
                            {#<span class="txt">{{ language.translate('Google +') }}</span></a></li>#}
                    {#<li class="tw"><a href="https://twitter.com/_lotteries">#}
                            {#<svg class="ico v-twitter">#}
                                {#<use xlink:href="/w/svg/icon.svg#v-twitter"></use>#}
                            {#</svg>#}
                            {#<span class="txt">{{ language.translate('Twitter') }}</span></a></li>#}
                {#</ul>#}
            {#</div>#}
            {#{% include "_elements/logo.volt" %}#}
        {#</div>#}
    {#</aside>#}

    {#TODO : remove this comments#}
    {#<div class="info cl">#}
        {#<div class="wrapper">#}
            {#<div class="cols">#}
                {#<div class="col5 txt">#}
                    {#{{ language.translate('license') }}#}
                    {#<br><br>#}
                    {#{{ language.translate('copyright') }}#}
                {#</div>#}
                {#<div class="col8 box-partner">#}
                    {#<ul class="no-li inline">#}
                        {#<li><a href="http://www.visaeurope.com/">#}
                                {#<svg class="v-visa vector">#}
                                    {#<use xlink:href="/w/svg/icon.svg#visa"/>#}
                                {#</svg>#}
                            {#</a></li>#}
                        {#<li><a href="http://www.mastercard.com/eur/">#}
                                {#<svg class="v-mastercard vector">#}
                                    {#<use xlink:href="/w/svg/icon.svg#mastercard"/>#}
                                {#</svg>#}
                            {#</a></li>#}
                        {#<li><a href="http://www.gambleaware.co.uk/">#}
                                {#<svg class="v-gambleaware vector">#}
                                    {#<use xlink:href="/w/svg/icon.svg#gambleaware"/>#}
                                {#</svg>#}
                            {#</a></li>#}
                        {#<li>#}
                            {#<a href="https://ssl.comodo.com/"><img src="/w/svg/comodo.png"/> </a>#}
                        {#</li>#}
                    {#</ul>#}
                {#</div>#}
            {#</div>#}
        {#</div>#}
    {#</div>#}


    <div class="wrapper">
        <div class="cards-block">
            <div class="inner">

                <ul class="no-li inline">
                    <li>
                        <a href="http://www.visaeurope.com/" class="visa-a" title="Visa Europe">
                            <img src="/w/img/footer/desktop/visa.png"/>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.mastercard.com/eur/" class="master-a" title="Mastercard">
                            <img src="/w/img/footer/desktop/mastercard.png"/>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.paysafecard.com/" class="paysafe-a" title="Paysafecard">
                            <img src="/w/img/footer/desktop/paysafecard.png"/>
                        </a>
                    </li>
                    <li>
                        <a href="www.neteller.com/‎" class="neteller-a" title="Neteller">
                            <img src="/w/img/footer/desktop/neteller.png"/>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.skrill.com/en/" class="skrill-a" title="Skrill">
                            <img src="/w/img/footer/desktop/skrill.png"/>
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>


    <div class="wrapper">
        <div class="copyright-text">

            {#TODO : remove this comments#}
            {#<p>#}
                {#This service operates under the Gaming License #5536/JAZ authorised and regulated by the Government of#}
                {#Curaçao. This site is operated by#}
                {#Panamedia B.V., Emancipatie Boulevard 29, Willemstad, Curaçao and payment processing services are#}
                {#provided by Panamedia International#}
                {#Limited, 30/3 Sir Augustus Bartolo Street, XBX 1093, Ta Xbiex Malta (EU).#}
            {#</p>#}
            {#<p>#}
                {#All transactions are charged in Euros. Prices displayed in other currencies are for informative purposes#}
                {#only and are convertedaccording to#}
                {#actual exchange rates.#}

            {#</p>#}
            {#<p>#}
                {#Copyright © 2011-2016 by EuroMillions.com. <br>#}
                {#All rights reserved.#}
            {#</p>#}

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
