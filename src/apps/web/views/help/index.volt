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
                    <h1 class="h1 title yellow">{{ language.translate("Help: How to play") }}</h1>
                    <em class="sub-txt">{{ language.translate("Playing Euromillions on Euromillions.com costs only %bet_price% (%bet_price_pound%) per play, the best price available on the Internet.",['bet_price':bet_price,'bet_price_pound':bet_price_pound]) }}</em>

                    <picture class="pic">
                        <img class="img" src="/w/img/help/bg-help.jpg" srcset="/w/img/help/bg-help@2x.jpg 1.5x" alt="{{ language.translate('A pen and a lottery ticket with marked numbers') }}">
                    </picture>

                    <ul class="ul-help no-li cl">
                        <li class="choose cl">
                            <div class="box-ico">
                                <svg class="ico v-pencil"><use xlink:href="/w/svg/icon.svg#v-pencil"></use></svg>
                            </div>
                            <div class="content">
                                <h3 class="li-title">{{ language.translate("Choose your numbers") }}</h3>
                                {{ language.translate("Complete a Euromillions play slip by selecting <em>five</em> main numbers from 1 to 50 and <em>two</em> Lucky Stars from 1 to 11. Alternatively, click on") }} <div class='sample btn gwy'><svg class="ico v-shuffle"><use xlink:href="/w/svg/icon.svg#v-shuffle"/></svg></div> or <div class='sample btn bwb'>{{ language.translate("Randomize all lines") }} <svg class="ico v-shuffle"><use xlink:href="/w/svg/icon.svg#v-shuffle"/></svg></div> {{ language.translate("to generate a random selection of numbers.") }}
                            </div>
                        </li>
                        <li class="slip cl">
                           <div class="box-ico">
                                <svg class="ico v-clover"><use xlink:href="/w/svg/icon.svg#v-clover"></use></svg>
                            </div>
                            <div class="content">
                                <h3 class="li-title">{{ language.translate("Play slips") }}</h3>
                                {{ language.translate("There are 6 lines per play slip on standard PC or laptop screens (or less if you access from a smaller screen, mobile or tablet). To add more lines, click on") }} <div class='sample btn gwg'>{{ language.translate("Add more lines") }} <svg class="ico v-shuffle"><use xlink:href="/w/svg/icon.svg#v-shuffle"/></svg></div> {{ language.translate("whereby more play slips will appear.") }}
                            </div>
                        </li>
                        <li class="cart cl">
                           <div class="box-ico">
                                <svg class="ico v-cart"><use xlink:href="/w/svg/icon.svg#v-cart"></use></svg>
                            </div>
                            <div class="content">
                                <h3 class="li-title">{{ language.translate("Next") }}</h3>
                                {{ language.translate('Once happy with your choice of numbers click on')}} <div class='sample btn purple'><div class="gap"></div>{{ language.translate("Next") }}</div> {{ language.translate('where you will be guided through the payment process.') }}
                            </div>
                        </li>
                        <li class="confirm cl">
                           <div class="box-ico">
                                <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>
                            </div>
                            <div class="content">
                                <h3 class="li-title">{{ language.translate("Order Confirmation") }}</h3>
                                {{ language.translate("On completion of payment you will receive a message on the website confirming that the numbers you have played and the draw you have entered. You can see all the lines and tickets you have purchased at any time in your player account.") }}
                            </div>
                        </li>
                        <li class="notify cl">
                           <div class="box-ico">
                                <svg class="ico v-email64"><use xlink:href="/w/svg/icon.svg#v-email64"></use></svg>
                            </div>
                            <div class="content">
                                <h3 class="li-title">{{ language.translate("Results") }}</h3>
                                {{ language.translate("Shortly after the draw you will receive an email notification detailing the latest results and if you have won. Any winnings will automatically be credited to your player account.") }}
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col5 box-faq">
                    <h2 class="h2 title purple">{{ language.translate("FAQ") }}</h2>

                    <div class="questions">
                        <h3 class="h4">{{ language.translate("Euromillions Basics") }}</h2>
                        <ul class="no-li">
                            <li><a href="/{{ lottery }}/faq#n01">{{ language.translate("What is the Euromillions lottery?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n02">{{ language.translate("What is Euromillions.com?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n03">{{ language.translate("How do I play?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n04">{{ language.translate("What time is the draw?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n05">{{ language.translate("How do I know that I won?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n06">{{ language.translate("When the Draw results are released?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n07">{{ language.translate("What is the Prize Breakdown?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n08">{{ language.translate("What is the minimum guaranteed jackpot?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n09">{{ language.translate("What is a Superdraw?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n11">{{ language.translate("How can I participate in a future draw?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n13">{{ language.translate("How do I make a bet with multiple numbers in a line?") }}</a></li>
                        </ul>

{#
                        <h3 class="h4">{{ language.translate("Euromillions Advanced Play") }}</h2>
                        <ul class="no-li">    
                            <li><a href="/faq#n10">{{ language.translate("What is a Long Play?") }}</a></li> 
                            <li><a href="/faq#n12">{{ language.translate("Can I play only when the Jackpot Prize reach a specific amount?") }}</a></li>   
                        </ul>
#}
                       <h3 class="h4">{{ language.translate("Winnings") }}</h2>
                        <ul class="no-li">
                            <li><a href="/{{ lottery }}/faq#n14">{{ language.translate("How do I know if I have won a prize?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n15">{{ language.translate("How do I claim a prize?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n16">{{ language.translate("Are winnings on the Euromillions taxable?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n17">{{ language.translate("What are the odds of winning?") }}</a></li>
                        </ul>

                        <h3 class="h4">{{ language.translate("Account and Billings") }}</h2>
                        <ul class="no-li">
                            <li><a href="/{{ lottery }}/faq#n18">{{ language.translate("How much does a Euromillions ticket cost?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n19">{{ language.translate("What payment options and currency are accepted?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n20">{{ language.translate("What time do ticket sales close?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n21">{{ language.translate("Does the Euromillions have a jackpot cap?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n22">{{ language.translate("How do I track my past played games?") }}</a></li>
                        </ul>

                        <h3 class="h4">{{ language.translate("Troubleshootings") }}</h2>
                        <ul class="no-li">
                            <li><a href="/{{ lottery }}/faq#n23">{{ language.translate("What should I do if I am experiencing technical problems?") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n24">{{ language.translate("I have forgotten my password and I cannot login. What do I do?") }}</a></li>
{#                            <li><a href="/faq#n25">{{ language.translate("How do I edit or delete a Subscription?") }}</a></li> #}
                            <li><a href="/{{ lottery }}/faq#n26">{{ language.translate("How I can disable emails notifications?") }}</a></li>
                        </ul>

                        <h3 class="h4">{{ language.translate("Legal") }}</h2>
                        <ul class="no-li">
                            <li><a href="/{{ lottery }}/faq#n27">{{ language.translate("Can I play on Euromillions from any country") }}</a></li>
                            <li><a href="/{{ lottery }}/faq#n28">{{ language.translate("Is there a minimum age limit for playing?") }}</a></li>
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
                                {{ language.translate('There are 6 lines per play slip o n standard PC or laptop screens (or less if you access from a smaller screen, mobile or tablet). To add more lines, click on “add additional lines” whereby more play slips will appear.') }}
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
