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
            <h1 class="h1 title yellow">{{ language.translate("Cookies Info") }}</h1>
            <p>
                <strong>Visiting Euromillions.com websites with your browser settings adjusted to accept cookies or using Euromillions.com  mobile apps tells us that you want to use Euromillion's products and services and that you consent to our use of cookies and other technologies to provide them to you as described in this notice and in the <a href="/legal/">Terms &amp; Conditions</a> and <a href="/legal/legal/">Legal Info</a>.</strong>
            </p>
            <h2 class="h3 title yellow">{{ language.translate("What are Cookies and how we use them") }}</h2>
            <p>
                A cookie is a small piece of text sent to your browser by a website you visit. It helps the website to remember information about your visit, like your preferred language and other settings. That can make your next visit easier and the site more useful to you. Cookies play an important role. Without them, using the web would be a much more frustrating experience. 
            </p>
            <p>We use cookies for many purposes, for example:</p>
            <ul class="list">
                <li><strong>Preferences</strong> These cookies allow our websites to remember information that changes the way the site behaves or looks, such as your preferred language or currency.</li>
                <li><strong>Security</strong> We use security cookies to authenticate users, prevent fraudulent use of login credentials, and protect user data from unauthorized parties.</li>
                <li><strong>Analytics</strong> We use analytics tool that helps us understand how visitors engage with the website, and we use a set of cookies to collect information and report website usage statistics without personally identifying individual visitors.</li>
            </ul>
        </div>
    </div>
</main>
{% endblock %}

