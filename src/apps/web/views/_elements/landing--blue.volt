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
                    <img src="/w/img/logo/v2/logo-desktop.png" alt="Euromillions">
                </a>
                <ul class="ul-top-nav">
                    <li class="li-sign">
                        <a rel="nofollow" class="btn-theme btn-secondary ui-link" href="/sign-up">Sign Up</a>
                    </li>
                    <li class="li-sign">
                        <a rel="nofollow" class="btn-theme btn-primary ui-link" href="/sign-in">Login</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="content">
        <main id=content>

            <div class="landing--banner-block">

                <div class="landing--banner-block--content">
                    <div class="wrapper-small">

                        <div class="landing--banner-block--title">
                            Win a huge jackpot of
                        </div>
                        <div class="landing--banner-block--prize">
                            45m€
                        </div>
                        <div class="landing--banner-block--countdown-block">
                            <div class="lefter"></div>
                            <div class="righter">
                                <div class="top">
                                    next draw
                                </div>
                                <div class="bottom">
                                    3 days : 22hrs : 45 min
                                </div>
                            </div>
                        </div>
                        <div class="landing--banner-block--button-row">
                            <a rel="nofollow" class="btn-theme btn-secondary" href="/sign-in">Join us</a>
                        </div>
                        <div class="landing--banner-block--star">
                            *Every Friday and Tuesday a new draw to become a millionare
                        </div>

                    </div>
                </div>
            </div>


            <div class="wrapper wrapper--arrows">
                <div class="landing--arrows">
                    <ul>
                        <li class="li-01"><span><i>1.</i>Join Us</span></li>
                        <li class="li-02"><span><i>2.</i> select your <br>numbers</span></li>
                        <li class="li-03"><span><i>3.</i> win big</span></li>
                    </ul>
                </div>
            </div>
            <div class="landing--disclaimer">
                <div class="wrapper">
                    <p>
                        Only play if you are 18+. This service operates under the Gaming License #5536/JAZ authorised and regulated
                        by the Government of Curaçao. This site is operated by Panamedia B.V., Emancipatie Boulevard29,
                        <br>
                        Willemstad, Curaçao and payment processing services are provided by Panamedia International Limited, 30/3
                        Sir Augustus Bartolo Street, XBX 1093, Ta Xbiex Malta (EU). All transactions are charged in Euros. Prices
                        <br>
                        displayed in other currencies are for informative purposes only and are converted according to actual
                        exchange rates. 
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>
</body>