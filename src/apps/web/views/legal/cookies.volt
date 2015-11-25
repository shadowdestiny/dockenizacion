{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/legal.css">{% endblock %}
{% block bodyClass %}cookies{% endblock %}

{% block header %}
    {% set activeNav='{"myClass":""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass":"cookies"}'|json_decode %}
           {% include "legal/_nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title yellow">{{ language.translate("Cookies") }}</h1>
            <p>
                {{ language.translate('<strong>Visiting Euromillions.com websites with your browser settings adjusted to accept cookies or using Euromillions.com mobile apps, tells us that you want to use of Euromillion products and services and that you consent to our use of cookies and other technologies to provide them to you as described in this notice and in the <a href="/legal/">Terms &amp; Conditions</a> and <a href="/legal/legal/">Legal Info</a>.') }}</strong>
            </p>
            <h2 class="h3 title yellow">{{ language.translate("What are Cookies and how we use them") }}</h2>
            <p>
               {{ language.translate("A cookie is a small piece of text sent to your browser by a website you visit. It helps the website to remember information about your visit, like your preferred language and other settings. That can make your next visit easier and the site more useful to you. Cookies play an important role. Without them, using the web would be a much more frustrating experience.") }} 
            </p>
            <p>{{ language.translate('We use cookies for many purposes, for example:') }}</p>
            <ul class="list">
                <li>{{ language.translate("<strong>Preferences</strong> - These cookies allow our websites to remember information that changes the way the site behaves or looks, such as your preferred language or currency.") }}</li>
                <li>{{ language.translate("<strong>Security</strong> - We use security cookies to authenticate users, prevent fraudulent use of login credentials, and protect user data from unauthorized parties.") }}</li>
                <li>{{ language.translate("<strong>Analytics</strong> - We use analytics tool that helps us understand how visitors engage with the website, and we use a set of cookies to collect information and report website usage statistics without personally identifying individual visitors.") }}</li>
            </ul>

            <h2 class="h3 title yellow">{{ language.translate("Third parties cookies") }}</h2>
            <p>{{ language.translate('To obtain better statistics relating to access to this portal and test better ways to make this website more usable and interesting for our users, we use the third party service of <a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/cookie-usage">Google Analytics</a>.') }}</p>


            <h2 class="h3 title yellow">{{ language.translate("More informations on Cookies") }}</h2>
            <ul class="list">
                <li><a href="http://www.agpd.es/portalwebAGPD/index-ides-idphp.php">{{ language.translate('Agencia Española de Proteción de datos') }}</a></li>
                <li><a href="https://en.wikipedia.org/wiki/HTTP_cookie">Wikipedia</a></li>
                <li><a href="http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm">{{ language.translate('The EU Internet Handbook') }}</a></li>
            </ul>

            <h2 class="h3 title yellow">{{ language.translate("How disable cookies") }}</h2>
            <p>{{ language.translate('You can find information on how disable cookies by following any of those resources:') }}</p>
            <ul class="list">
                <li>
                    <a href="https://support.google.com/chrome/answer/95647?hl=es">Google Chrome</a>
                </li>
                <li>
                    <a href="http://support.mozilla.org/en-US/kb/enable-and-disable-cookies-website-preferences?redirectlocale=en-US&redirectslug=Enabling+and+disabling+cookies">Firefox</a>
                </li>
                <li>
                    <a href="http://support.microsoft.com/kb/278835">Microsoft Internet Explorer</a>
                </li>
                <li>
                    <a href="http://support.apple.com/kb/PH11913">Safari</a>
                </li>
                <li>
                    <a href="http://support.apple.com/kb/HT1677?viewlocale=en_US&locale=en_US">Safari iOS (iPad, iPhone, iPod touch)</a>
                </li>
                <li>
                    <a href="http://www.wikihow.com/Disable-Cookies#Android_Devices">Android</a>
                </li>
                <li>
                    <a href="http://www.opera.com/help/tutorials/security/privacy/">Opera</a>
                </li>
                <li>
                    <a href="http://docs.blackberry.com/en/smartphone_users/deliverables/32004/Turn_off_cookies_in_the_browser_60_1072866_11.jsp">Blackberry</a>
                </li>
                <li><a href="http://www.wikihow.com/Disable-Cookies">Wikihow</a></li>
            </ul>
        </div>
    </div>
</main>
{% endblock %}

