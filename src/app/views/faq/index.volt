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

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic medium">
            <a id="top"></a>
            <h1 class="h1 title">Frequently Asked Questions</h1>

            <div class="questions">
                <h2 class="h3">Euromillions Basics</h2>
                <ul class="no-li">
                    <li><a href="#">What is Euromillion?</a></li>
                    <li><a href="#n01">What time is the draw?</a></li>
                    <li><a href="#n07">How do I play Euromillions?</a></li>
                    <li><a href="#n12">How long does it take for the results to be released?</a></li>
                    <li><a href="#n10">When I win playing Euromillions?</a></li> 
                </ul>

                <h2 class="h3">Euromillions Advanced Play</h2>
                <ul class="no-li">
                    <li><a href="#n17">What is a Euromillions Superdraw?</a></li>
                    <li><a href="#n18">What is a Euromillions Subscription?</a></li> 
                    <li><a href="#n08">How can I participate in a future draw?</a></li>
                    <li><a href="#">Can I play only when the Jackpot Prize reach a specific ammount?</a></li>
                    <li><a href="#">How do I make a bet with multiple numbers in a line?</a></li>
                </ul>

               <h2 class="h3">Winnings</h2>
                <ul class="no-li">
                    <li><a href="#n11">What are the odds of winning?</a></li>
                    <li><a href="#n14">How do I know if I have won a prize?</a></li>
                    <li><a href="#n16">How do I claim a prize?</a></li>
                    <li><a href="#n15">How long do I have to claim my prize?</a></li>
                    <li><a href="#n19">Are winnings on the Euromillions taxable?</a></li>
                </ul>

                <h2 class="h3">Account and Billings</h2>
                <ul class="no-li">
                    <li><a href="#n02">How much does a Euromillions ticket cost?</a></li>
                    <li><a href="#n09">What payment options and currency are accepted?</a></li>
                    <li><a href="#n03">What time do ticket sales close?</a></li>
                    <li><a href="#n13">Does the Euromillions have a rollover limit or jackpot cap?</a></li> 
                    <li><a href="#">How do I track my past played games?</a></li>
                </ul>

                <h2 class="h3">Troubleshootings</h2>
                <ul class="no-li">
                    <li><a href="#n20">I have forgotten my password and I cannot login. What do I do?</a></li>
                    <li><a href="#">How do I edit or delete a Euromillions Subscription?</a></li>
                    <li><a href="#">I had problems to complete a purchase of a ticket. Who do I contact for help?</a></li>
                </ul>

                <h2 class="h3">Legal</h2>
                <ul class="no-li">
                    <li><a href="#n06">What is the minimum guaranteed jackpot?</a></li>
                    <li><a href="#n05">Can I play on Euromillions from any country</a></li>
                    <li><a href="#n04">Is there a minimum age limit for playing?</a></li>
                </ul>
            </div>
            <div class="answer">
                <a id=""></a>
                <h3 class="h4">What is Euromillion?</h3>
                <p>Euromillions.com is the first lottery based website built to work on every device and every screen size, no matter how large or small. Mobile or desktop, we will always offer you the best user experience.
                <br>Your time is valuable to us, so we work hard to provide you with a quick, smart, and reliable experience to play lottery online from the comfort of your home or on the go.
                <br>Your fate-changer might be right here in the palm of your hand.
                <br>We understand what you expect from us and we assure you that your winnings are commission free and will remain so forever.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="multiple-line"></a>
                <h3 class="h4">How do I make a bet with multiple numbers in a line?</h3>
                <p>At the moment of launching our new improved version of Euromillions, we are not supporting this feature. In the close future we are commited to re-introduce it and to give you the best lotto experience that you expect.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n01"></a>
                <h3 class="h4">What time is the Euromillions draw?</h3>
                <p><a href="javascript:void(0)">The Euromillions (link play page) draws</a> take place on Tuesday and Friday evenings at approximately 21:45 CET.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n07"></a>
                <h3 class="h4">How do I play Euromillions?</h3>
                <p>Information regarding how to play Euromillions is available in detail on the <a href="javascript:void(0)">How to play</a> page.</p> 
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n02"></a>
                <h3 class="h4">How much does a Euromillions ticket cost?</h3>
                <p><a href="javascript:void(0)">Playing Euromillions (link play page)</a> costs &euro;2.35/&pound;1.65 per play. This is the best price available on the Internet.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n03"></a>
                <h3 class="h4">What time do Euromillions ticket sales close?</h3>
                <p>Tickets sales close at 20:30 CET for both draws on Tuesday and Friday. Tickets sales are then validated for the next Euromillions draw.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n05"></a>
                <h3 class="h4">Can I play Euromillions from any country?</h3>
                <p>Yes. Anyone from around the world can play through Euromillions.com on the condition that gambling is not prohibited in their country of residence.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n04"></a>
                <h3 class="h4">Is there a minimum age limit for playing Euromillions?</h3>
                <p>To be eligible to play Euromillions, all participants must be 18 years or over (16+ in the UK).</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n06"></a>
                <h3 class="h4">What is the minimum guaranteed jackpot?</h3>
                <p>The minimum guaranteed jackpot is &euro;15 million (approx. &pound;10.5 million). It can rollover until &euro;190 million.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n08"></a>
                <h3 class="h4">Can I participate in a future Euromillions draw?</h3>
                <p>Yes, you can purchase a play for a future Euromillions draw by clicking "Advance Play" in the <a href="javascript:void(0);">Play Page</a>, and selecting the numbers of draws you would like your ticket(s) to participate in or by selecting a future date on which you would like to play.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n09"></a>
                <h3 class="h4">What payment options and currency are accepted?</h3>
                <p>We accept credit and debit cards and all currencies. Transactions are made in Euros.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n10"></a>
                <h3 class="h4">When I win playing Euromillions?</h3>
                <p>To win the Euromillions jackpot (Value current Jackpot) players need to match all 5 main numbers and 2 Lucky Stars. To win a Euromillions prize, players they just have to match 2 or more numbers. It is that easy to be a winner!</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n11"></a>
                <h3 class="h4">What are the odds of winning Euromillions?</h3>
                <p>The odds of winning the Euromillions jackpot are approximately 116,531,800 to 1. The odds of winning a Euromillions prize is 1 in 23.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n12"></a>
                <h3 class="h4">How long does it take for the Euromillions results to be released?</h3>
                <p>The latest Euromillions results are revealed approximately an hour following the draw (22:30 CET). A full breakdown of prizes is released another hour later (23:30 CET). However, in the event of a large jackpot the processing and verification of the latest results can take up until midnight to finalise. You can get the latest Euromillions results on our results page.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>
                
                <a id="n13"></a>
                <h3 class="h4">Does the Euromillions have a rollover limit or jackpot cap?</h3>
                <p>Currently there is no limit as to the number of times the jackpot can rollover however, a jackpot pool cap of &euro;190 million (approx. &pound;161 million) does exist. Thus, the Euromillions jackpot will continue to roll over to the subsequent draw in the event of no first prize winner until the &euro;190 million jackpot cap is reached.  In addition, it can only remain at the aforementioned sum for another two draws with funds accumulated from any further rollovers cascading down to the next tier with a winner. Similarly, if upon the second draw no first prize winner is produced, the jackpot will roll down to the next prize tier (level) featuring a winner.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n14"></a>
                <h3 class="h4">How do I know if I have won a prize?</h3>
                <p>Shortly after each draw you will receive the latest Euromillions results directly in your email. Alternatively, players can visit their online players account or our Results Page featuring a full breakdown of prizes.</p> 
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n16"></a>
                <h3 class="h4">How do I claim a prize?</h3>
                <p>All winnings are automatically accredited to players' accounts shortly after the relevant Euromillions draw. Large prizes are transferred to the players personal bank account (without commissions) when a scanned copy of the winner's ID or passport has been provided.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n15"></a>
                <h3 class="h4">How long do I have to claim my prize?</h3>
                <p>With Euromillions.com all prizes must be claimed within 2 months (60 days) prior to the draw taking place. Failure to do so within the given time will result in the forfeiting of your prize. During the aforementioned claim period we will do our very best to inform clients of any winnings using the contact details provided in their online players account.</p> 
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n17"></a>
                <h3 class="h4">What is a Euromillions Superdraw?</h3>
                <p>A Euromillions Superdraw is a special draw which typically features a guaranteed &euro;100 million jackpot (approx. &pound;70 million) whether or not the Euromillions jackpot was won in the preceding draw. They usually occur once or twice a year in celebration of a special event or to mark changes in the lottery however, they can happen at any time. Similarly to a normal Euromillions draw, if no one matches the 5 main numbers and two lucky stars, the jackpot is rolled over to the next draw.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n18"></a>
                <h3 class="h4">What is a Euromillions subscription?</h3>
                <p>A subscription comprises a recurring ticket, which ensures that you never miss a lottery draw. You can easily set one by using Advance Play in the Play page.</p> 
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n19"></a>
                <h3 class="h4">Are winnings on the Euromillions taxable?</h3>
                <p>Most countries do not tax winnings. We advice you to check if in your country of residence gambling is taxable.</p>
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>

                <a id="n20"></a>
                <h3 class="h4">I have forgotten my password and I cannot login. What do I do?</h3>
                <p>If you have forgotten your password you can <a href="javascript:void(0)">easily reset it</a>. If your account has all ready been blocked please contact our customer support to resolve the problem. <a href="mailto:support@euromillions.com">support@euromillions.com</a></p> 
                <a href="#top" class="back"><i class="ico ico-arrow-up2"></i> Back to the top</a>
            </div>

        </div>
    </div>
</main>
{% endblock %}