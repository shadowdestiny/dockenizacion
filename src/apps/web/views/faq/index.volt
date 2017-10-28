{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/faq.css">
    <!--[if IE 9]>
    <style>.laurel {
        display: none;
    }</style>
    <![endif]-->
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
    {#$(function(){#}
    {#var hash = window.location.hash;#}
    {#$(document.body).animate({#}
    {#'scrollTop':   $('#'+hash.split('#')[1]).offset().top#}
    {#}, 100);#}
    {#});#}
{% endblock %}
{% block body %}

    <main id="content">

        <div class="faq--page" data-ajax="false">
            <div class="banner"></div>

            <div class="wrapper">

                <div class="title-block">
                    <h1>
                        How to Play, faq ?
                    </h1>
                </div>


                <div class="content">
                    <div class="right-section">

                        <section class="section-01">
                            <div class="corner"></div>
                            <div class="title">
                                Euro Millions
                            </div>
                            <div class="price">
                                €148
                            </div>
                            <div class="measure">
                                millions
                            </div>

                            <div class="timer">
                                {% include "_elements/countdown.volt" %}
                            </div>

                            <div class="btn-row">
                                <a href="/{{ language.translate("link_euromillions_play") }}"
                                   class="btn-theme--big">
                                    Play now
                                </a>
                            </div>
                        </section>

                        <section class="section-02">
                            <div class="corner"></div>
                            <div class="title">
                                powerball
                            </div>
                            <div class="price">
                                €14
                            </div>
                            <div class="measure">
                                millions
                            </div>

                            <div class="timer">
                                {% include "_elements/countdown.volt" %}
                            </div>

                            <div class="btn-row">
                                <a href="/{{ language.translate("link_euromillions_play") }}"
                                   class="btn-theme--big">
                                    Play now
                                </a>
                            </div>
                        </section>

                    </div>


                    <div class="faq-section">

                        <div class="questions"  style="display: none;">
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

                            <h3 class="h3">{{ language.translate("eurom_no1_q") }}</h3>
                            <p>{{ language.translate("eurom_no1_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no2_q") }}</h3>
                            <p>{{ language.translate("eurom_no2_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no3_q") }}</h3>
                            <p>{{ language.translate("eurom_no3_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no4_q") }}</h3>
                            <p>{{ language.translate("eurom_no4_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no5_q") }}</h3>
                            <p>{{ language.translate("eurom_no5_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no6_q") }}</h3>
                            <p>{{ language.translate("eurom_no6_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no7_q") }}</h3>
                            <p>{{ language.translate("eurom_no7_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no8_q") }}</h3>
                            <p>{{ language.translate("eurom_no8_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no9_q") }}</h3>
                            <p>{{ language.translate("eurom_no9_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no11_q") }}</h3>
                            <p>{{ language.translate("eurom_no11_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no12_q") }}</h3>
                            <p>{{ language.translate("eurom_no12_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no13_q") }}</h3>
                            <p>{{ language.translate("eurom_no13_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no14_q") }}</h3>
                            <p>{{ language.translate("eurom_no14_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no15_q") }}</h3>
                            <p>{{ language.translate("eurom_no15_a",['bet_price': bet_price,'bet_price_pound' : bet_price_pound]) }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no16_q") }}</h3>
                            <p>{{ language.translate("eurom_no16_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no17_q") }}</h3>
                            <p>{{ language.translate("eurom_no17_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no18_q") }}</h3>
                            <p>{{ language.translate("eurom_no18_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no19_q") }}</h3>
                            <p>{{ language.translate("eurom_no19_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no20_q") }}</h3>
                            <p>{{ language.translate("eurom_no20_a",['email_support':email_support]) }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no21_q") }}</h3>
                            <p>{{ language.translate("eurom_no21_a",['email_support':email_support]) }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no22_q") }}</h3>
                            <p>{{ language.translate("eurom_no22_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no23_q") }}</h3>
                            <p>{{ language.translate("eurom_no23_a") }}</p>

                            <h3 class="h3">{{ language.translate("eurom_no24_q") }}</h3>
                            <p>{{ language.translate("eurom_no24_a") }}</p>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </main>
{% endblock %}