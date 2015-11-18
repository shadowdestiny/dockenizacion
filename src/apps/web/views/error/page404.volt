{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/error.css">
    <!--[if IE 9]>
    <style>.laurel{display:none;}</style>
    <![endif]-->
{% endblock %}
{% block bodyClass %}error{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block body %}
<main id="content">
    <div class="bg">
        <div class="wrapper">
            <h1 class="h1 message yellow">{{ language.translate("The page you requested was not found - ERROR 404") }}</h1>
            <h2 class="h0 title">{{ language.translate("Sorry, we just ran out of magic.") }}</h2>
            <p class="p">
                {{ language.translate('Our Genies are currently recharging their magic dust or Aladdin is asking too many wishes at once,
                <br class="br">either way we are unable to realize all the wishes requested. We apologise for the inconvenience.') }}
            </p>
            <form class="box-results">
                <p class="last-known">The last known wish <span class="yellow">"win the lottery"</span> was</p>
                <ul class="no-li inline numbers">
                    <li><span class="num">10</span></li>
                    <li><span class="num">17</span></li>
                    <li><span class="num">18</span></li>
                    <li><span class="num">33</span></li>
                    <li><span class="num">40</span></li>
                    <li class="star"><span class="num">2</span><span class="txt">Star ball</span></li>
                    <li class="star"><span class="num">8</span><span class="txt">Star ball</span></li>
                </ul>
                <a href="javascript:void(0)" class="btn big blue submit">Play magically selected numbers</a>
            </form>
        </div>
    </div>
    <div class="looking">
        <div class="wrapper">
            <h2 class="h1 title2">Or maybe you were looking for</h2>
            <ul class="no-li cl h3">
                <li><a href="/play">Playing the Lottery</a></li>
                <li><a href="/numbers">Draw History</a></li>
                <li><a href="/faq">Asking for help</a></li>
                <li><a href="/help">How to Play Lotto</a></li>
                <li><a href="/index#about-us">About Euromillions</a></li>
                <li><a href="/contact">Contact us</a></li>
            </ul>
        </div>
    </div>
</main>
{% endblock %}