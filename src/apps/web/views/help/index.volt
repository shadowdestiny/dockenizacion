{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/help.css">{% endblock %}
{% block bodyClass %}help{% endblock %}

{% block header %}
{% set activeNav='{"myClass": "help"}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic">
            <div class="cols">
                <div class="col7 box-how-to">
                    <h1 class="h1 title yellow">{{ language.translate("help_head") }}</h1>
                    <em class="sub-txt">{{ language.translate("help_subhead",['price':bet_price,'priceGBP':bet_price_pound]) }}</em>

                    <picture class="pic">
                        <img class="img" src="/w/img/help/bg-help.jpg" srcset="/w/img/help/bg-help@2x.jpg 1.5x" alt="{{ language.translate('A pen and a lottery ticket with marked numbers') }}">
                    </picture>

                    <ul class="ul-help no-li cl">
                        <li class="choose cl">
                            <div class="box-ico">
                                <svg class="ico v-pencil"><use xlink:href="/w/svg/icon.svg#v-pencil"></use></svg>
                            </div>
                            <div class="content">
                                <h3 class="li-title">{{ language.translate("step1_subhead") }}</h3>
                                {{ language.translate("step1_text") }}
                            </div>
                        </li>
                        <li class="slip cl">
                           <div class="box-ico">
                                <svg class="ico v-clover"><use xlink:href="/w/svg/icon.svg#v-clover"></use></svg>
                            </div>
                            <div class="content">
                                <h3 class="li-title">{{ language.translate("step2_subhead") }}</h3>
                                {{ language.translate("step2_text") }}
                            </div>
                        </li>
                        <li class="cart cl">
                           <div class="box-ico">
                                <svg class="ico v-cart"><use xlink:href="/w/svg/icon.svg#v-cart"></use></svg>
                            </div>
                            <div class="content">
                                <h3 class="li-title">{{ language.translate("step3_subhead") }}</h3>
                                {{ language.translate("step3_text") }}
                            </div>
                        </li>
                        <li class="confirm cl">
                           <div class="box-ico">
                                <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>
                            </div>
                            <div class="content">
                                <h3 class="li-title">{{ language.translate("step4_subhead") }}</h3>
                                {{ language.translate("step4_text") }}
                            </div>
                        </li>
                        <li class="notify cl">
                           <div class="box-ico">
                                <svg class="ico v-email64"><use xlink:href="/w/svg/icon.svg#v-email64"></use></svg>
                            </div>
                            <div class="content">
                                <h3 class="li-title">{{ language.translate("step5_subhead") }}</h3>
                                {{ language.translate("step5_text") }}
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col5 box-faq">
                    <h2 class="h2 title purple">{{ language.translate("eurom_head") }}</h2>

                    <div class="questions">
                        <h3 class="h4">{{ language.translate("eurom_subhead_basics") }}</h3>
                        <ul class="no-li">
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n01">{{ language.translate("eurom_no1_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n02">{{ language.translate("eurom_no2_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n03">{{ language.translate("eurom_no3_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n04">{{ language.translate("eurom_no4_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n05">{{ language.translate("eurom_no5_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n06">{{ language.translate("eurom_no6_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n07">{{ language.translate("eurom_no7_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n08">{{ language.translate("eurom_no8_q") }}</a></li>
                            <li><a href="/{{{ language.translate("link_euromillions_faq") }}#n09">{{ language.translate("eurom_no9_q") }}</a></li>
{#                          <li><a href="/{{ language.translate("link_euromillions_faq") }}#n11">{{ language.app("eurom_no10_q") }}</a></li> #}
{#                          <li><a href="/{{ language.translate("link_euromillions_faq") }}#n13">{{ language.translate("How do I make a bet with multiple numbers in a line?") }}</a></li>#}
                        </ul>

{#
                        <h3 class="h4">{{ language.app("Euromillions Advanced Play") }}</h2>
                        <ul class="no-li">    
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n10">{{ language.app("What is a Long Play?") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n12">{{ language.app("Can I play only when the Jackpot Prize reach a specific amount?") }}</a></li>
                        </ul>
#}
                       <h3 class="h4">{{ language.translate("eurom_subhead_winnings") }}</h3>
                        <ul class="no-li">
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n14">{{ language.translate("eurom_no11_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n15">{{ language.translate("eurom_no12_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n16">{{ language.translate("eurom_no13_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n17">{{ language.translate("eurom_no14_q") }}</a></li>
                        </ul>

                        <h3 class="h4">{{ language.translate("eurom_subhead_account") }}</h3>
                        <ul class="no-li">
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n18">{{ language.translate("eurom_no15_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n19">{{ language.translate("eurom_no16_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n20">{{ language.translate("eurom_no17_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n21">{{ language.translate("eurom_no18_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n22">{{ language.translate("eurom_no19_q") }}</a></li>
                        </ul>

                        <h3 class="h4">{{ language.translate("eurom_subhead_trouble") }}</h3>
                        <ul class="no-li">
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n23">{{ language.translate("eurom_no20_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n24">{{ language.translate("eurom_no21_q") }}</a></li>
{#                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n25">{{ language.app("How do I edit or delete a Subscription?") }}</a></li> #}
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n26">{{ language.translate("eurom_no22_q") }}</a></li>
                        </ul>

                        <h3 class="h4">{{ language.translate("eurom_subhead_legal") }}</h3>
                        <ul class="no-li">
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n27">{{ language.translate("eurom_no23_q") }}</a></li>
                            <li><a href="/{{ language.translate("link_euromillions_faq") }}#n28">{{ language.translate("eurom_no24_q") }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
{#
        <div class="cols">
            <div class="col7">
                <div class="box-basic">
                    <div class="bg">
                        <h1 class="h1 title yellow">{{ language.app("Help") }}</h1>
                        <h2 class="h3 purple">{{ language.app("How to play") }}</h2>
                    </div>
                    <div class="wrap">
                        <p class="txt">{{ language.app("Playing Euromillions on Euromillions.com costs only %bet_price% (%bet_price_pound%) per play, the best price available on the Internet.",['bet_price':bet_price,'bet_price_pound':bet_price_pound]) }}</p>

                        <ol class="ul-help no-li cl">
                            <li>
                                <h3 class="li-title">{{ language.app("1. Choose your numbers") }}</h3>
                                {{ language.app("Complete a Euromillions play slip by selecting 5 main numbers from 1 to 45 and two Lucky Stars from 1 to 11. Alternatively, click on “randomize all lines” to generate a random selection of numbers.") }}
                            </li>
                            <li>
                                <h3 class="li-title">{{ language.app("2. Play Slip") }}</h3>
                                {{ language.app('There are 6 lines per play slip o n standard PC or laptop screens (or less if you access from a smaller screen, mobile or tablet). To add more lines, click on “add additional lines” whereby more play slips will appear.') }}
                            </li>
                            <li>
                                 <h3 class="li-title">{{ language.app("3. Duration") }}</h3>
                                 {{ language.app("Select the days in which you would like your ticket to participate (Tuesday or Friday or both) and the number of draws you would like them to participate in. You can play up to 52 weeks (104 draws) in advance or opt for a subscription.") }}
                            </li>
                            <li>
                                <h3 class="li-title">{{ language.app("4. Add to Cart") }}</h3>
                                {{ language.app('Once happy with your choice of numbers click on "add to cart" where you will be guided through the payment process.') }}
                            </li>
                            <li>
                                <h3 class="li-title">{{ language.app("5. Confirmation") }}</h3>
                                {{ language.app("On completion of payment you will receive a message on the website confirming that the numbers you have played and the draws you have entered. Any running games that you have played are as well accessible anytime in your player account.") }}
                            </li>
                            <li>
                                <h3 class="li-title">{{ language.app("6. Notification") }}</h3>
                                {{ language.app("Shortly after the draw you will receive an email notification detailing the latest results and if you have won. Any winnings will automatically be credited to your player account.") }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col5">
                <picture class="pic img-help">
                    <!--[if IE 9]><video style="display: none;"><![endif]-->
                    <source media="(max-width: 768px)" srcset="/w/img/help/help-sm.png">
                    <!--[if IE 9]></video><![endif]-->
                    <img src="/w/img/help/help.png" srcset="/w/img/help/help.png, /w/img/help/help@2x.png 1.5x" alt="{{ language.app('How to play') }}">
                </picture>
            </div>
        </div>
#}
    </div>
</main>

{% endblock %}
