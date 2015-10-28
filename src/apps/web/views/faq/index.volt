{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/faq.css">
    <!--[if IE 9]>
    <style>.laurel{display:none;}</style>
    <![endif]-->
{% endblock %}
{% block bodyClass %}faq{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
    //EMTD we use this function as workaround from jquery mobile to anchor link via url
    <script>
        $(function() {
            var hash = window.location.hash;
            $(document.body).animate({
                'scrollTop':   $('#'+hash.split('#')[1]).offset().top
            }, 100);
        });
    </script>
{% endblock %}
{% block body %}

<main id="content">
    <div class="wrapper">
        <div class="box-basic medium" data-ajax="false">
            <a id="top"></a>
            <h1 class="h1 title">{{ language.translate("Frequently Asked Questions") }}</h1>

            <div class="questions">
                <h2 class="h3">{{ language.translate("Euromillions Basics") }}</h2>
                <ul class="no-li">
                    <li><a href="#n01">{{ language.translate("What is the Euromillions lottery?") }}</a></li>
                    <li><a href="#n02">{{ language.translate("What is Euromillions.com?") }}</a></li>
                    <li><a href="#n03">{{ language.translate("How do I play?") }}</a></li>
                    <li><a href="#n04">{{ language.translate("What time is the draw?") }}</a></li>
                    <li><a href="#n05">{{ language.translate("How do I know that I won?") }}</a></li> 
                    <li><a href="#n06">{{ language.translate("When the Draw results are released?") }}</a></li>
                    <li><a href="#n07">{{ language.translate("What is the Prize Breakdown?") }}</a></li>
                    <li><a href="#n08">{{ language.translate("What is the minimum guaranteed jackpot?") }}</a></li>
                </ul>

                <h2 class="h3">{{ language.translate("Euromillions Advanced Play") }}</h2>
                <ul class="no-li">
                    <li><a href="#n09">{{ language.translate("What is a Superdraw?") }}</a></li>

                    <li><a href="#n10">{{ language.translate("What is a Long Play?") }}</a></li> 

{#
                    <li><a href="#n07">{{ language.translate("What is a Subscription?") }}</a></li> 
#}

                    <li><a href="#n11">{{ language.translate("How can I participate in a future draw?") }}</a></li>
                    <li><a href="#n12">{{ language.translate("Can I play only when the Jackpot Prize reach a specific ammount?") }}</a></li>
                    <li><a href="#n13">{{ language.translate("How do I make a bet with multiple numbers in a line?") }}</a></li>
                </ul>

               <h2 class="h3">{{ language.translate("Winnings") }}</h2>
                <ul class="no-li">
                    <li><a href="#n14">{{ language.translate("How do I know if I have won a prize?") }}</a></li>
                    <li><a href="#n15">{{ language.translate("How do I claim a prize?") }}</a></li>
                    <li><a href="#n16">{{ language.translate("Are winnings on the Euromillions taxable?") }}</a></li>
                    <li><a href="#n17">{{ language.translate("What are the odds of winning?") }}</a></li>
                </ul>

                <h2 class="h3">{{ language.translate("Account and Billings") }}</h2>
                <ul class="no-li">
                    <li><a href="#n18">{{ language.translate("How much does a Euromillions ticket cost?") }}</a></li>
                    <li><a href="#n19">{{ language.translate("What payment options and currency are accepted?") }}</a></li>
                    <li><a href="#n20">{{ language.translate("What time do ticket sales close?") }}</a></li>
                    <li><a href="#n21">{{ language.translate("Does the Euromillions have a jackpot cap?") }}</a></li> 
                    <li><a href="#n22">{{ language.translate("How do I track my past played games?") }}</a></li>
                </ul>

                <h2 class="h3">{{ language.translate("Troubleshootings") }}</h2>
                <ul class="no-li">
                    <li><a href="#n23">{{ language.translate("What should I do if I am experiencing technical problems?") }}</a></li>
                    <li><a href="#n24">{{ language.translate("I have forgotten my password and I cannot login. What do I do?") }}</a></li>
                    <li><a href="#n25">{{ language.translate("How do I edit or delete a Subscription?") }}</a></li>
                    <li><a href="#n26">{{ language.translate("How I can disable emails notifications?") }}</a></li>
                </ul>

                <h2 class="h3">{{ language.translate("Legal") }}</h2>
                <ul class="no-li">
                    <li><a href="#n27">{{ language.translate("Can I play on Euromillions from any country") }}</a></li>
                    <li><a href="#n28">{{ language.translate("Is there a minimum age limit for playing?") }}</a></li>
                </ul>
            </div>

            <div class="answer">
                <h2 class="h2 yellow">{{ language.translate("Euromillions Basics") }}</h2>

                <a id="n01"></a>
                <h3 class="h3">{{ language.translate("What is the Euromillions lottery?") }}</h3>
                <p>{{ language.translate('Euromillions is the biggest european transnational lottery featuring a minimum 15 million euro jackpot every Tuesday and Friday. The jackpot can rollover until it reaches 190 million euro if there are no winners.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n02"></a>
                <h3 class="h3">{{ language.translate("What is Euromillions.com") }}</h3>
                <p>{{ language.translate('Euromillions.com is the first lottery based website built to work on every device and every screen size, no matter how large or small. Mobile or desktop, we will always offer you the best user experience.
                <br><br>Your time is valuable to us, so we work hard to provide you with a quick, smart, and reliable experience to play lottery online from the comfort of your home or on the go. Your fate-changer might be right here in the palm of your hand.
                <br><br>We understand what you expect from us and we assure you that your winnings are commission free and will remain so forever.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n03"></a>
                <h3 class="h3">{{ language.translate("How do I play?") }}</h3>

                <p>{{ language.translate('Information regarding how to play Euromillions is available in detail on <a href="%link%">How to play</a>.',['link':url("help")]) }}</p> 
                {% include "faq/back-top.volt" %}

                <a id="n04"></a>
                <h3 class="h3">{{ language.translate("What time is the draw?") }}</h3>
                <p>{{ language.translate('<a href="%link%">Euromillions draws</a> take place on Tuesday and Friday evenings at approximately 21:45 CET.',['link':url("play")]) }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n05"></a>
                <h3 class="h3">{{ language.translate("How do I know that I won?") }}</h3>
                <p>{{ language.translate('To win the Euromillions jackpot (Value current Jackpot) players need to match all 5 main numbers and 2 Lucky Stars. To win a Euromillions prize, players they just have to match 2 or more numbers. It is that easy to be a winner!') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n06"></a>
                <h3 class="h3">{{ language.translate("When the Draw results are released?") }}</h3>
                <p>{{ language.translate('The latest <a href="/numbers">Euromillions results</a> are revealed approximately an hour following the draw 22:30 CET. A full breakdown of prizes is released another hour later 23:30 CET. However, in the event of a large jackpot the processing and verification of the latest results can take up until midnight to finalise. You can get the latest Euromillions results on our results page.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n07"></a>
                <h3 class="h3">{{ language.translate("What is the Prize Breakdown?") }}</h3>
                <p>{{ language.translate('The Euromillions lottery has a total of 13 prize tiers. The prize breakdown shows you the amount of winnings per prize tier. You can see in <a href="/numbers">our results</a> an example of prize breakdown.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n08"></a>
                <h3 class="h3">{{ language.translate('What is the minimum guaranteed jackpot?')}}</h3>
                <p>{{ language.translate('The minimum guaranteed jackpot is &euro; 15 million. It can rollover until &euro; 190 million.') }}</p>
                {% include "faq/back-top.volt" %}

                <h2 class="h2 yellow">{{ language.translate("Euromillions Advanced Play") }}</h2>

                <a id="n09"></a>
                <h3 class="h3">{{ language.translate("What is a Superdraw?") }}</h3>
                <p>{{ language.translate('A Euromillions Superdraw is a special draw which typically features a guaranteed &euro; 100 million jackpot whether or not the Euromillions jackpot was won in the preceding draw. They usually occur once or twice a year in celebration of a special event or to mark changes in the lottery however, they can happen at any time. Similarly to a normal Euromillions draw, if no one matches the 5 main numbers and two lucky stars, the jackpot is rolled over to the next draw.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n10"></a>
                <h3 class="h3">{{ language.translate("What is a Long Play?") }}</h3>
                <p>{{ language.translate('A Long Play is a recurring ticket. You can participate in the coming weeks without repeating the payment process. Do not worry, we will send you an email notification when your Long Play comes to an end. Remember, that you can opt again for a Long Play or a Subscription without any kind of commitment.')}}</p> 
                {% include "faq/back-top.volt" %}
{#
                <a id="n07"></a>
                <h3 class="h3">{{ language.translate("What is a Subscription?") }}</h3>
                <p>{{ language.translate('A subscription comprises a recurring ticket, which ensures that you never miss a lottery draw. Subscription are without commitment and you can stop it any time.',['link':url("play")]) }}</p> 
                {% include "faq/back-top.volt" %}
#}
                <a id="n11"></a>
                <h3 class="h3">{{ language.translate("How can I participate in a future draw?") }}</h3>
                <p>{{ language.translate('Yes, you can purchase a play for a future Euromillions draw by clicking on Advance Play in the <a href="%link%">Play Page</a>, and selecting the numbers of draws you would like your ticket(s) to participate in or by selecting a future date on which you would like to play.',['link':url("play")]) }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n12"></a>
                <h3 class="h3">{{ language.translate("Can I play only when the Jackpot Prize reach a specific ammount?") }}</h3>
                <p>{{ language.translate('Yes, you can by clicking on Advance Play in the <a href="%link%">Play Page</a>',['link':url("play")]) }}</p> 
                {% include "faq/back-top.volt" %}
  
                <a id="n13"></a>
                <h3 class="h3">{{ language.translate("How do I make a bet with multiple numbers in a line?") }}</h3>
                <p>{{ language.translate("At the moment of launching our new improved version of Euromillions.com, we are not supporting this feature. In the close future we are commited to introduce multiple bets for your convenience.") }}</p>
                {% include "faq/back-top.volt" %}

                <h2 class="h2 yellow">{{ language.translate("Winnings") }}</h2>

                <a id="n14"></a>
                <h3 class="h3">{{ language.translate("How do I know if I have won a prize?") }}</h3>
                <p>{{ language.translate('Shortly after each draw that you played you will receive the latest Euromillions results directly in your email. You can even set from your Account area to send always an email with the draw results even if you have not played. Alternatively, players can visit their online players account or our Results Page with the prize breakdown.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n15"></a>
                <h3 class="h3">{{ language.translate("How do I claim a prize?") }}</h3>
                <p>{{ language.translate('All winnings are automatically credited to your player account shortly after the draw. Big prizes are directly transferred to your private bank account without any comissions.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n16"></a>
                <h3 class="h3">{{ language.translate("Are winnings on the Euromillions taxable?") }}</h3>
                <p>{{ language.translate('Most countries do not tax winnings. We advice you to check if in your country of residence gambling is taxable.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n17"></a>
                <h3 class="h3">{{ language.translate("What are the odds of winning?") }}</h3>
                <p>{{ language.translate('The odds of winning the Euromillions jackpot are approximately 116,000,000 to 1. The odds of winning a Euromillions prize is 1 in 23.') }}</p>
                {% include "faq/back-top.volt" %}

                <h2 class="h2 yellow">{{ language.translate("Account and Billings") }}</h2>

                <a id="n18"></a>
                <h3 class="h3">{{ language.translate("How much does a Euromillions ticket cost?") }}</h3>
                <p>{{ language.translate('<a href="%link%">Playing Euromillions</a> costs &euro; 2.35 / &pound; 1.65 per play. This is the best price available on the Internet.',['link':url("play")]) }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n19"></a>
                <h3 class="h3">{{ language.translate("What payment options and currency are accepted?") }}</h3>
                <p>{{ language.translate('We accept credit and debit cards and all currencies. Transactions are made in Euros.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n20"></a>
                <h3 class="h3">{{ language.translate("What time do ticket sales close?") }}</h3>
                <p>{{ language.translate('Ticket sales closes at 19:00 Central European Time the day of the draw. Bets ordered after the cut of time will be validated for the next draw.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n21"></a>
                <h3 class="h3">{{ language.translate("Does the Euromillions have jackpot cap?") }}</h3>
                <p>{{ language.translate('The jackpot can rollover until a &euro; 190 million jackpot cap is reached. A 190 million Euro jackpot can remain only two consecutive draws and if there is no jackpot winner the big prize will cascade down to the following prize tier that features a winner.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n22"></a>
                <h3 class="h3">{{ language.translate("How do I track my past played games?") }}</h3>
                <p>{{ language.translate('In the <a href="%link%">Games area</a> section of your player account you can find a summary of all your past games. If you wish from this area you can try your luck with your previous played numbers.',['link':url("account/games")]) }}</p>
                {% include "faq/back-top.volt" %}

                <h2 class="h2 yellow">{{ language.translate("Troubleshootings") }}</h2>

                <a id="n23"></a>
                <h3 class="h3">{{ language.translate("What should I do if I am experiencing technical problems?") }}</h3>
                <p>{{ language.translate('You should contact <a href="mailto:support@euromillions.com">support@euromillions.com</a> describing in detail your problem, which kind of device, browser and operative system you are using, and possibly provide a screenshot of the issue.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n24"></a>
                <h3 class="h3">{{ language.translate("I have forgotten my password and I cannot login. What do I do?") }}</h3>
                <p>{{ language.translate('If you have forgotten your password you can <a href="%link%">easily reset it</a>. If your account has all ready been blocked please contact our customer support to resolve the problem. <a href="mailto:support@euromillions.com">support@euromillions.com</a>',['link':url("reset")]) }}</p> {# EMTD - Insert reset link #}
                {% include "faq/back-top.volt" %}

                <a id="n25"></a>
                <h3 class="h3">{{ language.translate("How do I edit or delete a Subscription?") }}</h3>
                <p>{{ language.translate('In the <a href="%link%">Games area</a> section of your player account you can find all your active bets. In there you can easily modify duration, numbers and ammount of partecipating bets per draw.',['link':url("account/games")]) }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n26"></a>
                <h3 class="h3">{{ language.translate("How I can disable emails notifications?") }}</h3>
                <p>{{ language.translate('In the <a href="%link%">Email Settings area</a> section of your player you can easily configure your email notifications preferences.',['link':url("account/email")]) }}</p>
                {% include "faq/back-top.volt" %}

                <h2 class="h2 yellow">{{ language.translate("Legal") }}</h2>

                <a id="n27"></a>
                <h3 class="h3">{{ language.translate("Can I play Euromillions from any country?") }}</h3>
                <p>{{ language.translate('Yes, We are a Fully licensed gambling operator. Anyone from around the world can play through Euromillions.com on the condition that gambling is not prohibited in their country of residence.') }}</p>
                {% include "faq/back-top.volt" %}

                <a id="n28"></a>
                <h3 class="h3">{{ language.translate("Is there a minimum age limit for playing?") }}</h3>
                <p>{{ language.translate('To be eligible to play Euromillions, all participants must be 18 years or over (16+ in the UK).') }}</p>
                {% include "faq/back-top.volt" %}
            </div>

        </div>
    </div>
</main>
{% endblock %}