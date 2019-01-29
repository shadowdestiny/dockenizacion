<!DOCTYPE html>
<html>
<head>
    {% include "_elements/meta.volt" %} {# META tags #}
    <link rel="icon" type="image/png" href="/w/img/logo/favicon.png" />

    {# CSS Compress this css in a single file #}
    <link rel="stylesheet" href="/w/css/main.css">
    <link rel="stylesheet" href="/w/css/main_v2.css">
    <link rel="stylesheet" href="/w/css/vendor/jquery.countdownTimer.css">
    {% block template_css %}{% endblock %}      {# Inject unique css #}

    {# FONTS  #}
    {#<link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700'>#}
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,600,700,900" rel="stylesheet">
    <script type="text/javascript" src="//script.crazyegg.com/pages/scripts/0074/6139.js" async="async"></script>

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function()

        {n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)}
        ;
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '165298374129776');
        fbq('track', 'PageView');
    </script>
    {{ tracking }}
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=165298374129776&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->
    {% block font %}{% endblock %}
</head>
<body class="{% if user_currency is defined %}{% if user_currency['symbol']|length > 1 %}cur-txt {% endif %}{{ currency_css(user_currency_code) }}{% endif %} {% block bodyClass %}{% endblock %}">
<div class="landing landing--blue">
    <header data-role="header" class="landing--header">

        <nav class="landing--top-nav">
            <div class="wrapper">
                <a href="/" class="logo logo-desktop-a ui-link" title="Go to Homepage">
                    <img src="https://images.euromillions.com/imgs/logo-desktop.png" alt="Euromillions">
                </a>
            </div>
        </nav>
    </header>
    <div class="content">
        <main id=content>

            <div class="landing--banner-block">

                <div class="landing--banner-block--content landing--banner-block--content--lottery {{landing_lottery_class}}-bg">
                    <div class="wrapper-small">

                        <div class="landing--banner-block--title">
                            {{ language.translate("Landing_"~lottery~"_title1") }}<br>
                            {{ language.translate("Landing_title2") }}
                        </div>
                        <div class="landing--banner-block--title-mobile">
                            {{ language.translate("Landing_title_mobile1") }}<br>
                            {{ language.translate("Landing_title_mobile2") }}
                        </div>
                        <div class="landing--banner-block--prize">
                            {{landing_jackpot_value}}{% if landing_jackpot_milliards %}B {% elseif landing_jackpot_trillions %}T {% else %}M {% endif %}
                        </div>
                        <div class="landing--banner-block--title-mobile-bottom">
                            {{ language.translate("Landing_"~lottery~"_title_mobile3") }}
                        </div>
                        <div class="landing--banner-block--countdown-block">
                         <strong>{{ language.translate("Landing_nextdraw") }}    {% if landing_show_day['days']>0 %}{{landing_show_day['days']}}</strong>D{%else%}</strong>{% endif %} <strong>{{landing_show_day['hours']}}</strong>H:<strong>{{landing_show_day['minutes']}}</strong>M
                        </div>
                        <div class="landing--banner-block--button-row">
                            <a rel="nofollow" class="btn-theme btn-secondary" href="/{{ language.translate("link_signup") }}">{{ language.translate("Landing_buttoncalltoaction") }}</a>
                        </div>
                        <div class="landing--banner-block--star">
                            {{ language.translate("Landing_"~lottery~"_text_dates") }}
                        </div>

                    </div>
                </div>
            </div>


            <div class="wrapper wrapper--arrows">
                <div class="landing--arrows">
                    <ul>
                        <li class="li-01"><span><i><strong>1</strong>|</i> {{ language.translate("Landing_button1") }}</span></li>
                        <li class="li-02"><span><i><strong>2</strong>|</i> {{ language.translate("Landing_button2") }}</span></li>
                        <li class="li-03"><span><i><strong>3</strong>|</i> {{ language.translate("Landing_button3") }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="landing--disclaimer">
                <div class="wrapper">
                    <p>
                        {{ language.translate("Landing_legalfooter") }} 
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
