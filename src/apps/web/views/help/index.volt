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
        <div class="cols">
            <div class="col8">
                <h1 class="h1 title yellow">{{ language.translate("Help: How to play") }}</h1>
            </div>
            <div class="col4">
            
            </div>
        </div>
{#
        <div class="cols">
            <div class="col7">
                <div class="box-basic">
                    <div class="bg">
                        <h1 class="h1 title yellow">{{ language.translate("Help") }}</h1>
                        <h2 class="h3 purple">{{ language.translate("How to play") }}</h2>
                    </div>
                    <div class="wrap">
                        <p class="txt">{{ language.translate("Playing Euromillions on Euromillions.com costs only %bet_price% (%bet_price_pound%) per play, the best price available on the Internet.",['bet_price':bet_price,'bet_price_pound':bet_price_pound]) }}</p>

                        <ol class="ul-help no-li cl">
                            <li>
                                <h3 class="li-title">{{ language.translate("1. Choose your numbers") }}</h3>
                                {{ language.translate("Complete a Euromillions play slip by selecting 5 main numbers from 1 to 45 and two Lucky Stars from 1 to 11. Alternatively, click on “randomize all lines” to generate a random selection of numbers.") }}
                            </li>
                            <li>
                                <h3 class="li-title">{{ language.translate("2. Play Slip") }}</h3>
                                {{ language.translate("There are 6 lines per play slip (or less if you access from a mobile device). To play further lines, click on “add additional lines” whereby more play slips will appear.") }}
                            </li>
                            <li>
                                 <h3 class="li-title">{{ language.translate("3. Duration") }}</h3>
                                 {{ language.translate("Select the days in which you would like your ticket to participate (Tuesday or Friday or both) and the number of draws you would like them to participate in. You can play up to 52 weeks (104 draws) in advance or opt for a subscription.") }}
                            </li>
                            <li>
                                <h3 class="li-title">{{ language.translate("4. Add to Cart") }}</h3>
                                {{ language.translate('Once happy with your choice of numbers click on "add to cart" where you will be guided through the payment process.') }}
                            </li>
                            <li>
                                <h3 class="li-title">{{ language.translate("5. Confirmation") }}</h3>
                                {{ language.translate("On completion of payment you will receive a message on the website confirming that the numbers you have played and the draws you have entered. Any running games that you have played are as well accessible anytime in your player account.") }}
                            </li>
                            <li>
                                <h3 class="li-title">{{ language.translate("6. Notification") }}</h3>
                                {{ language.translate("Shortly after the draw you will receive an email notification detailing the latest results and if you have won. Any winnings will automatically be credited to your player account.") }}
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
                    <img src="/w/img/help/help.png" srcset="/w/img/help/help.png, /w/img/help/help@2x.png 1.5x" alt="{{ language.translate('How to play') }}">
                </picture>
            </div>
        </div>
#}
    </div>
</main>

{% endblock %}