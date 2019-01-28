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
    <script src="/w/js/vendor/jquery-1.11.3.min.js"></script>
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
    <script>
    $(document).ready(function () {
        $( "#sign-up-form" ).on('change', '#country', function() {
                $.ajax({
                    url:'https://restcountries.eu/rest/v2/name/'+$("#country option:selected").text()+'?fullText=true',
                    type:'get',
                    dataType:"json",
                    success:function(json){
                        $("#prefix").html('<option value="">Prefix</option>');
                        $.each(json[0].callingCodes, function( index, value ) {
                            $("#prefix").append('<option value="'+value+'" selected="selected">'+value+'</option>');
                        });
                    },
                    error:function (xhr, status, errorThrown){
                       //Manage Errors
                        },
                    });
        });
    });
    </script>
    {% block font %}{% endblock %}
    <!-- CODE FROM sign-in/sign-up.volt -->
    {% block template_scripts_after %}<script src="/w/js/react/tooltip.js"></script>{% endblock %}
    {% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}
    <!-- END CODE FROM sign-in/sign-up.volt -->
</head>
{% block body %}
<div class="landing landing--orange">
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

                <div class="landing--banner-block--content landing--banner-block--content--lottery">
                    <div class="wrapper">
                        <div class="landing--banner-block--content--left">
                          <div class="landing--banner-block--title">
                              Play <strong>POWERBALL</strong> and<br>
                              <strong>Win</strong> a huge JACKPOT of
                          </div>
                          <div class="landing--banner-block--title-mobile">
                              <strong>Win</strong> a<br>huge JACKPOT of
                          </div>
                          <div class="landing--banner-block--prize">
                              €945m
                          </div>
                          <div class="landing--banner-block--title-mobile-bottom">
                              with <strong>POWERBALL</strong>
                          </div>
                          <div class="landing--banner-block--countdown-block">
                              <strong>next draw 3</strong>D <strong>22</strong>H:<strong>45</strong>M
                          </div>

                          <div class="landing--banner-block--star">
                              Every Friday and Tuesday a new draw to become a millionare
                          </div>

                          <div class="landing--banner-block--steps">
                            <ul>
                              <li class="li-01"><span><i><strong>1</strong>|</i> Join <strong>Us</strong></span></li>
                              <li class="li-02"><span><i><strong>2</strong>|</i> select your <strong>numbers</strong></span></li>
                              <li class="li-03"><span><i><strong>3</strong>|</i> <strong>Big</strong> win</span></li>
                            </ul>
                          </div>
                        </div>
                        <!-- Sign up form starts -->

                        <div class="landing--banner-block--content--right">
                            <div class="signin-form landing--signin-form sign-up">
                              <h1 class="h1 title">{{ language.translate("signup_head") }}</h1>
                              <!-- CODE FROM sign-in/sign-up.volt -->
                              {% set signIn='{"myClass": "sign-in"}'|json_decode %}
                              {% set url_signup = '/sign-up' %}
                              {% include "sign-in/_sign-up.volt" %}
                              <!-- END CODE FROM sign-in/sign-up.volt -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="landing--disclaimer">
                <div class="wrapper">
                    <p>Only play if you are 18+. This service operates under the Gaming License #5536/JAZ authorised and regulated by the Government of Curaçao. This site is operated by Panamedia B.V., Emancipatie Boulevard29, Willemstad, Curaçao and payment processing services are provided by Panamedia International Limited, 30/3 Sir Augustus Bartolo Street, XBX 1093, Ta Xbiex Malta (EU). All transactions are charged in Euros. Prices displayed in other currencies are for informative purposes only and are converted according to actual exchange rates.</p>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
    function fbRegistration() {
        fbq('track', 'CompleteRegistration');
    }
</script>
{% if ga_code is defined %}
    <!--start PROD imports
    <script src="/w/js/dist/GASignUpAttempt.min.js"></script>
    end PROD imports-->
    <!--start DEV imports-->
    <script src="/w/js/GASignUpAttempt.js"></script>
    <!--end DEV imports-->
{% endif %}
{% endblock %}
