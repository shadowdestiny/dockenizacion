<!DOCTYPE html>
<html>
<head>
    {% include "_elements/meta.volt" %} {# META tags #}
    <link rel="icon" type="image/png" href="/w/img/logo/favicon.png" />

    {# CSS Compress this css in a single file #}
    <link rel="stylesheet" href="/w/css/main.css">
    <link rel="stylesheet" href="/w/css/vendor/jquery.countdownTimer.css">
    {% block template_css %}{% endblock %}      {# Inject unique css #}

    {# FONTS  #}
    {#<link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700'>#}
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,600,700,900" rel="stylesheet">
    {% block font %}{% endblock %}
</head>

<body class="{% if user_currency is defined %}{% if user_currency['symbol']|length > 1 %}cur-txt {% endif %}{{ currency_css(user_currency_code) }}{% endif %} {% block bodyClass %}{% endblock %}">
{% if countdown_finish_bet is defined %}
    {% if countdown_finish_bet|length != 0 %}
        <div id="countDownFinishBet" class="overlay-limit-bet">
            TIME LEFT
            <span id="m_timer"></span>
            <a href="/{{ language.translate("link_euromillions_play") }}" class="btn red small ui-link">PLAY NOW</a>
            <script>
                window.onload = function () {
                    $(function () {
                        $('#m_timer').countdowntimer({
                            hours: {{ countdown_finish_bet['hours'] }},
                            minutes: {{ countdown_finish_bet['minutes'] }},
                            seconds: {{ countdown_finish_bet['seconds'] }},
                            size: "sm",
                            borderColor: "#ae5279",
                            backgroundColor: "#ae5279",
                            fontColor: "#efc048"
                        });
                    });

                    if ( {{ countdown_finish_bet['diffTimeActualTimeAndNextDrawTime'] }} > {{ countdown_finish_bet['timeLimitAppearCountDown'] }} )
                    {
                        if (({{ countdown_finish_bet['diffTimeActualTimeAndNextDrawTime'] }} - {{ countdown_finish_bet['timeLimitAppearCountDown'] }} ) > {{ countdown_finish_bet['timeLeftCountDown'] }}) {
                            setTimeout(function () {
                                $('#countDownFinishBet').fadeOut('fast');
                            }, {{ countdown_finish_bet['timeLeftCountDown'] * 1000 }});
                            setTimeout(function () {
                                $('#countDownFinishBet').fadeIn('fast');
                            }, {{ countdown_finish_bet['timeAppearCountDownAgain'] * 1000 }});
                        }
                        setTimeout(function () {
                            $('#countDownFinishBet').fadeOut('fast');
                        }, {{ countdown_finish_bet['diffTimeActualTimeAndNextDrawTime'] * 1000 }});

                        if (window.location.pathname.split('/')[2] == 'play') {
                            setTimeout(function () {
                                showModalTicketCloseByLimitBet();
                            }, {{ countdown_finish_bet['diffTimeActualTimeAndNextDrawTime'] * 1000 }});
                        }
                    }
                }
            </script>
        </div>
    {% endif %}
{% endif %}

{% block modal %}{% endblock %}

<div data-role="page" id="main-page">
    {% block header %}{% endblock %}

    <div data-role="content">
        {% block body %}{% endblock %}
    </div>
    {% block footer %}{% endblock %}
</div>

{# EDTD To remove SUBNAV when not connected as account #}
{% block mobileNav %}
    {% if user_logged is defined and user_logged %}
        <div data-role="page" id="nav-account">
            <ul class="no-li" data-role="listview">
                <li><a href="/account" data-transition="slide"
                       data-direction="reverse">{{ language.translate("Account") }}</a></li>
                <li><a href="/profile/tickets/games" data-transition="slide"
                       data-direction="reverse">{{ language.translate("Tickets") }}</a></li>
                <li><a href="/account/wallet" data-transition="slide"
                       data-direction="reverse">{{ language.translate("Balance") }}</a></li>
                <li><a href="/profile/transactions" data-transition="slide"
                       data-direction="reverse">{{ language.translate("Transactions") }}</a></li>
                <li><a href="/account/email" data-transition="slide"
                       data-direction="reverse">{{ language.translate("Email Settings") }}</a></li>
                <li><a href="/account/password" data-transition="slide"
                       data-direction="reverse">{{ language.translate("Change Password") }}</a></li>
                <li><a href="/logout" data-transition="slide"
                       data-direction="reverse">{{ language.translate("Sign out") }}</a></li>
            </ul>
        </div>
    {% endif %}

    <div data-role="page" id="language">
        <ul class="no-li" data-role="listview">
            {% for currency in currency_list %}
                <li><a href="#main-page" onclick="globalFunctions.setCurrency('{{ currency.code }}')"
                       data-transition="slide" data-direction="reverse">{{ currency.name }}</a></li>
            {% endfor %}
        </ul>
    </div>

    <div data-role="page" id="changelanguage">
        <ul class="no-li" data-role="listview">
            {% if languages is defined %}
                {% for active_language in languages %}
                    <li><a href="#main-page" onclick="globalFunctions.setLanguage('{{ active_language.ccode }}')"
                           data-transition="slide"
                           data-direction="reverse">{{ language.translate(active_language.ccode) }}</a></li>
                {% endfor %}
            {% endif %}
        </ul>
    </div>
{% endblock %}

{#<div class="ending">#}
{#{{ language.translate("The draw will close in 30 minutes.") }}#}
{#</div>#}

<svg>
    {# Chrome need this #}
    {# White gradient logo #}
    <linearGradient id="d" gradientUnits="userSpaceOnUse" x1="16.777" y1="-135.945" x2="16.777" y2="-161.627"
                    gradientTransform="translate(21.248 882.618) scale(4.104)">
        <stop offset="0" stop-color="#FFD936"/>
        <stop offset="1" stop-color="#FFF"/>
    </linearGradient>
    {# Shadow to any svg #}
    <linearGradient id="e" gradientUnits="userSpaceOnUse" x1="31.034" y1="2.99" x2="31.034" y2="53.503">
        <stop offset="0" stop-color="#FEFAEC"/>
        <stop offset="1" stop-color="#F1D86F"/>
    </linearGradient>
</svg>

{% include "_elements/js-lib.volt" %} {# JS libraries #}
{% block template_scripts %}{% endblock %}  {# Inject unique js #}
<script>
    {% block template_scripts_code %}{% endblock %}  {# Inject unique js inside <script> tag #}
</script>
{% block template_scripts_after %}{% endblock %}
{% block modal %}
    {% if show_modal_winning is defined and show_modal_winning %}
        <!--start PROD imports
        <script src="/w/js/dist/CheckWin.min.js"></script>
        end PROD imports-->
        <!--start DEV imports-->
        <script src="/w/js/CheckWin.js"></script>

        <!--end DEV imports-->
        <a href="/account/wallet" id="win" class="modal win">
            <span class="btn-box"><span class="btn blue">{{ language.translate("View the prize") }}</span></span>
        </a>
    {% endif %}
{% endblock %}
</body>
</html>
