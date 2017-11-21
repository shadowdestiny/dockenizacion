{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/faq.css">
    <!--[if IE 9]><style>.laurel{display:none;}</style><![endif]-->
    <link Rel="Canonical" href="{{ language.translate('canonical_euromillions_faq') }}" />
{% endblock %}
{% block bodyClass %}faq{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>
{% endblock %}
{% block template_scripts_code %}
    {# EMTD we use this function as workaround from jquery mobile to anchor link via url #}
    $(function(){
    var hash = window.location.hash;
    $(document.body).animate({
    'scrollTop':   $('#'+hash.split('#')[1]).offset().top
    }, 100);
    });
{% endblock %}
{% block body %}

    <main id="content">
        <div class="wrapper">
            <div class="box-basic medium" data-ajax="false">
                <a id="top"></a>
                <h1 class="h1 title">{{ language.translate("eurom_head") }}</h1>
                <div class="questions">
                    <h2 class="h3">{{ language.translate("eurom_subhead_basics") }}</h2>
                    <ul class="no-li">
                        <li><a href="#n01">{{ language.translate("eurom_no1_q") }}</a></li>
                        <li><a href="#n02">{{ language.translate("eurom_no2_q") }}</a></li>
                        <li><a href="#n03">{{ language.translate("eurom_no3_q") }}</a></li>
                        <li><a href="#n04">{{ language.translate("eurom_no4_q") }}</a></li>
                        <li><a href="#n05">{{ language.translate("eurom_no5_q") }}</a></li>
                        <li><a href="#n06">{{ language.translate("eurom_no6_q") }}</a></li>
                        <li><a href="#n07">{{ language.translate("eurom_no7_q") }}</a></li>
                        <li><a href="#n08">{{ language.translate("eurom_no8_q") }}</a></li>
                        <li><a href="#n09">{{ language.translate("eurom_no9_q") }}</a></li>
                    {#  <li><a href="#n11">{{ language.translate("eurom_no10_q") }}</a></li> #}
                    {#  <li><a href="#n13">{{ language.app("How do I make a bet with multiple numbers in a line?") }}</a></li> #}
                    </ul>

                    {#
                                    <h2 class="h3">{{ language.app("Euromillions Advanced Play") }}</h2>
                                    <ul class="no-li">
                                        <li><a href="#n10">{{ language.app("What is a Long Play?") }}</a></li>
                                        <li><a href="#n07">{{ language.app("What is a Subscription?") }}</a></li>
                                        <li><a href="#n12">{{ language.app("Can I play only when the Jackpot Prize reach a specific amount?") }}</a></li>
                                    </ul>
                    #}

                    <h2 class="h3">{{ language.translate("eurom_subhead_winnings") }}</h2>
                    <ul class="no-li">
                        <li><a href="#n14">{{ language.translate("eurom_no11_q") }}</a></li>
                        <li><a href="#n15">{{ language.translate("eurom_no12_q") }}</a></li>
                        <li><a href="#n16">{{ language.translate("eurom_no13_q") }}</a></li>
                        <li><a href="#n17">{{ language.translate("eurom_no14_q") }}</a></li>
                    </ul>

                    <h2 class="h3">{{ language.translate("eurom_subhead_account") }}</h2>
                    <ul class="no-li">
                        <li><a href="#n18">{{ language.translate("eurom_no15_q") }}</a></li>
                        <li><a href="#n19">{{ language.translate("eurom_no16_q") }}</a></li>
                        <li><a href="#n20">{{ language.translate("eurom_no17_q") }}</a></li>
                        <li><a href="#n21">{{ language.translate("eurom_no18_q") }}</a></li>
                        <li><a href="#n22">{{ language.translate("eurom_no19_q") }}</a></li>
                    </ul>

                    <h2 class="h3">{{ language.translate("eurom_subhead_trouble") }}</h2>
                    <ul class="no-li">
                        <li><a href="#n23">{{ language.translate("eurom_no20_q") }}</a></li>
                        <li><a href="#n24">{{ language.translate("eurom_no21_q") }}</a></li>
                        {#                    <li><a href="#n25">{{ language.app("How do I edit or delete a Subscription?") }}</a></li> #}
                        <li><a href="#n26">{{ language.translate("eurom_no22_q") }}</a></li>
                    </ul>

                    <h2 class="h3">{{ language.translate("eurom_subhead_legal") }}</h2>
                    <ul class="no-li">
                        <li><a href="#n27">{{ language.translate("eurom_no23_q") }}</a></li>
                        <li><a href="#n28">{{ language.translate("eurom_no24_q") }}</a></li>
                    </ul>
                </div>

                <div class="answer">
                    <h2 class="h2 yellow">{{ language.translate("eurom_subhead_basics") }}</h2>

                    <a id="n01"></a>
                    <h3 class="h3">{{ language.translate("eurom_no1_q") }}</h3>
                    <p>{{ language.translate("eurom_no1_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n02"></a>
                    <h3 class="h3">{{ language.translate("eurom_no2_q") }}</h3>
                    <p>{{ language.translate("eurom_no2_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n03"></a>
                    <h3 class="h3">{{ language.translate("eurom_no3_q") }}</h3>
                    <p>{{ language.translate("eurom_no3_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n04"></a>
                    <h3 class="h3">{{ language.translate("eurom_no4_q") }}</h3>
                    <p>{{ language.translate("eurom_no4_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n05"></a>
                    <h3 class="h3">{{ language.translate("eurom_no5_q") }}</h3>
                    <p>{{ language.translate("eurom_no5_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n06"></a>
                    <h3 class="h3">{{ language.translate("eurom_no6_q") }}</h3>
                    <p>{{ language.translate("eurom_no6_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n07"></a>
                    <h3 class="h3">{{ language.translate("eurom_no7_q") }}</h3>
                    <p>{{ language.translate("eurom_no7_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n08"></a>
                    <h3 class="h3">{{ language.translate("eurom_no8_q")}}</h3>
                    <p>{{ language.translate("eurom_no8_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n09"></a>
                    <h3 class="h3">{{ language.translate("eurom_no9_q") }}</h3>
                    <p>{{ language.translate("eurom_no9_a") }}</p>
                    {% include "faq/back-top.volt" %}

{#                    <a id="n11"></a>
                    <h3 class="h3">{{ language.translate("eurom_no10_q") }}</h3>
                    <p>{{ language.translate("eurom_no10_a") }}</p>
                    {% include "faq/back-top.volt" %} #}

{#                    <a id="n13"></a>
                    <h3 class="h3">{{ language.app("How do I make a bet with multiple numbers in a line?") }}</h3>
                    <p>{{ language.app('At the moment of launching our new improved version of Euromillions.com, we are not supporting this feature. In the close future we are commited to introduce multiple bets for your convenience.') }}</p>
                    {% include "faq/back-top.volt" %}
#}
                    {#
                                    <h2 class="h2 yellow">{{ language.app("Euromillions Advanced Play") }}</h2>

                                    <a id="n10"></a>
                                    <h3 class="h3">{{ language.app("What is a Long Play?") }}</h3>
                                    <p>{{ language.app('A Long Play is a recurring ticket. You can participate in the coming weeks without repeating the payment process. Do not worry, we will send you an email notification when your Long Play comes to an end. Remember, that you can opt again for a Long Play or a Subscription without any kind of commitment.')}}</p>
                                    {% include "faq/back-top.volt" %}

                                    <a id="n07"></a>
                                    <h3 class="h3">{{ language.app("What is a Subscription?") }}</h3>
                                    <p>{{ language.app('A subscription comprises a recurring ticket, which ensures that you never miss a lottery draw. Subscription are without commitment and you can stop it any time.',['link':url("play")]) }}</p>
                                    {% include "faq/back-top.volt" %}

                                    <a id="n12"></a>
                                    <h3 class="h3">{{ language.app("Can I play only when the Jackpot Prize reach a specific amount?") }}</h3>
                                    <p>{{ language.app('Yes, you can by clicking on Advance Play in the <a href="%link%">Play Page</a>',['link':url("play")]) }}</p>
                                    {% include "faq/back-top.volt" %}
                    #}


                    <h2 class="h2 yellow">{{ language.translate("eurom_subhead_winnings") }}</h2>

                    <a id="n14"></a>
                    <h3 class="h3">{{ language.translate("eurom_no11_q") }}</h3>
                    <p>{{ language.translate("eurom_no11_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n15"></a>
                    <h3 class="h3">{{ language.translate("eurom_no12_q") }}</h3>
                    <p>{{ language.translate("eurom_no12_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n16"></a>
                    <h3 class="h3">{{ language.translate("eurom_no13_q") }}</h3>
                    <p>{{ language.translate("eurom_no13_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n17"></a>
                    <h3 class="h3">{{ language.translate("eurom_no14_q") }}</h3>
                    <p>{{ language.translate("eurom_no14_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <h2 class="h2 yellow">{{ language.translate("eurom_subhead_account") }}</h2>

                    <a id="n18"></a>
                    <h3 class="h3">{{ language.translate("eurom_no15_q") }}</h3>
                    <p>{{ language.translate("eurom_no15_a",['bet_price': bet_price,'bet_price_pound' : bet_price_pound]) }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n19"></a>
                    <h3 class="h3">{{ language.translate("eurom_no16_q") }}</h3>
                    <p>{{ language.translate("eurom_no16_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n20"></a>
                    <h3 class="h3">{{ language.translate("eurom_no17_q") }}</h3>
                    <p>{{ language.translate("eurom_no17_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n21"></a>
                    <h3 class="h3">{{ language.translate("eurom_no18_q") }}</h3>
                    <p>{{ language.translate("eurom_no18_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n22"></a>
                    <h3 class="h3">{{ language.translate("eurom_no19_q") }}</h3>
                    <p>{{ language.translate("eurom_no19_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <h2 class="h2 yellow">{{ language.translate("eurom_subhead_trouble") }}</h2>

                    <a id="n23"></a>
                    <h3 class="h3">{{ language.translate("eurom_no20_q") }}</h3>
                    <p>{{ language.translate("eurom_no20_a",['email_support':email_support]) }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n24"></a>
                    <h3 class="h3">{{ language.translate("eurom_no21_q") }}</h3>
                    <p>{{ language.translate("eurom_no21_a",['email_support':email_support]) }}</p>
                    {% include "faq/back-top.volt" %}

                    {#
                                    <a id="n25"></a>
                                    <h3 class="h3">{{ language.app("How do I edit or delete a Subscription?") }}</h3>
                                    <p>{{ language.app('In the <a href="%link%">Games area</a> section of your player account you can find all your active bets. In there you can easily modify duration, numbers and amount of partecipating bets per draw.',['link':url("account/games")]) }}</p>
                                    {% include "faq/back-top.volt" %}
                    #}

                    <a id="n26"></a>
                    <h3 class="h3">{{ language.translate("eurom_no22_q") }}</h3>
                    <p>{{ language.translate("eurom_no22_a")}}</p>
                    {% include "faq/back-top.volt" %}

                    <h2 class="h2 yellow">{{ language.translate("eurom_subhead_legal") }}</h2>

                    <a id="n27"></a>
                    <h3 class="h3">{{ language.translate("eurom_no23_q") }}</h3>
                    <p>{{ language.translate("eurom_no23_a") }}</p>
                    {% include "faq/back-top.volt" %}

                    <a id="n28"></a>
                    <h3 class="h3">{{ language.translate("eurom_no24_q") }}</h3>
                    <p>{{ language.translate("eurom_no24_a") }}</p>
                    {% include "faq/back-top.volt" %}
                </div>

            </div>
        </div>
    </main>
{% endblock %}
