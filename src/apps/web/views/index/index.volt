{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/home.css">{% endblock %}

{% block bodyClass %}
home
{% include "_elements/jlength.volt" %}
{% endblock %}

{% block header %}
<a id="top"></a>
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}
{% block font %}<link href='https://fonts.googleapis.com/css?family=Signika:700' rel='stylesheet' type='text/css'>{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
<script src="/w/js/mobileFix.min.js"></script>
{%  if ga_code is defined %}
<!--start PROD imports
<script src="/w/js/dist/GASignUpLanding.min.js"></script>
end PROD imports-->
<!--start DEV imports-->
<script src="/w/js/GASignUpLanding.js"></script>
<!--end DEV imports-->
{% endif %}
{% endblock %}

{% block template_scripts_code %}
    {# EMTD we use this function as workaround from jquery mobile to anchor link via url #}
    $(function(){
        var hash = window.location.hash;
		var elem = hash.split('#')[1];
		console.log(elem);
		var scrollTop = typeof elem == 'undefined' ? 0 : $('#'+hash.split('#')[1]).offset().top;
        $(document.body).animate({
            'scrollTop': scrollTop
        }, 100);
    });
{% endblock %}

{% block body %}
<main id="content">
	<div class="large wrapper">
		<div class="banner">
			<div class="box-jackpot">
				<div class="info">{{ language.translate("EuroMillions Jackpot") }}</div>
				<div class="jackpot">
					<svg class="value">
					    <defs>
							<linearGradient id="e" x1="0" y1="0" x2="0" y2="1">
								<stop offset="30%"  stop-color="#fdf7e0"/>
								<stop offset="70%" stop-color="#f1d973"/>
							</linearGradient>
							<filter id="shadow" height="130%">
								<feOffset in="SourceAlpha" dx=".5" dy="1" result="myGauss"/>
								<feGaussianBlur in="myGauss" stdDeviation="2" result="myBlur" />
							    <feBlend in="SourceGraphic" in2="myBlur"/>
							</filter>
						</defs>
						{% set jackpot_val =  jackpot_value  %}
 						<g class="normal"> 
							<text filter="url(#shadow)">
								<tspan class="mycur" y="90"></tspan>
								<tspan class="mytxt" dx="10px" y="90">{{ jackpot_val }}</tspan>
							</text>
							<text fill="url(#e)">
								<tspan class="mycur" y="90"></tspan>
								<tspan class="mytxt" dx="10px" y="90">{{ jackpot_val }}</tspan>
							</text>
						</g>
						<g class="small n1" transform="translate(360)">
							<g text-anchor="middle" x="0">
								<text filter="url(#shadow)" y="80">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt" dx="10px">{{ jackpot_val }}</tspan>
								</text>
								<text  fill="url(#e)" y="80">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt" dx="10px">{{ jackpot_val }}</tspan>
								</text>
							</g>
						</g>
						<g class="small n2" transform="translate(280)">
							<g text-anchor="middle" x="0">
								<text filter="url(#shadow)" y="80">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt" dx="10px">{{ jackpot_val}}</tspan>
								</text>
								<text fill="url(#e)" y="80">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt" dx="10px">{{ jackpot_val }}</tspan>
								</text>
							</g>
						</g>
						<g class="small n3" transform="translate(240)">
							<g text-anchor="middle" x="0">
								<text filter="url(#shadow)" y="70">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt">{{ jackpot_val }}</tspan>
								</text>
								<text fill="url(#e)" y="70">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt">{{ jackpot_val }}</tspan>
								</text>
							</g>
						</g>
						<g class="small n4" transform="translate(210)">
							<g text-anchor="middle" x="0">
								<text filter="url(#shadow)" y="50">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt">{{ jackpot_val }}</tspan>
								</text>
								<text  fill="url(#e)" y="50">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt">{{ jackpot_val }}</tspan>
								</text>
							</g>
						</g>
						<g class="small n5" transform="translate(165)">
							<g text-anchor="middle" x="0">
								<text filter="url(#shadow)" y="50">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt">{{ jackpot_val }}</tspan>
								</text>
								<text  fill="url(#e)" y="50">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt">{{ jackpot_val }}</tspan>
								</text>
							</g>
						</g>
						<g class="small n6" transform="translate(135)">
							<g text-anchor="middle" x="0">
								<text filter="url(#shadow)" y="45">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt">{{ jackpot_val }}</tspan>
								</text>
								<text fill="url(#e)" y="45">
									<tspan class="mycur"></tspan>
									<tspan class="mytxt">{{ jackpot_val }}</tspan>
								</text>
							</g>
						</g>
					</svg>
				</div>
			</div>
			<div class="btn-box">
				<a href="/{{ lottery }}/play" class="btn red huge">{{ language.translate("PLAY NOW") }}</a>
				<div class="for-only">{{ language.translate("For only")}} {{ bet_price }}</div>
			</div>
			<div class="txt">{{ language.translate("Join the Club. Become our next EuroMillionaire!") }}</div>
			<div class="best-price">
				<picture class="pic">
					<img width="60" height="59" src="/w/img/home/best-price.png" srcset="/w/img/home/best-price@2x.png 1.5x" alt="{{ language.translate('Best Price Guarantee') }}">
				</picture>
			</div>
		</div>
	</div>

	<div class="wrapper">
		<div class="partners cl">
			<div class="list">
				<svg class="laurel first">
					<filter id="mygray"><feColorMatrix values="0" type="saturate"/></filter><style>.greyed{@media all and (-ms-high-contrast:none), (-ms-high-contrast: active){filter:url(#mygray);}}{# ie10 require it #+ csshack 10+#}</style>
					<use class="greyed" xlink:href="/w/svg/icon.svg#laurel"/>
				</svg>
				<ul class="no-li cl">
					<li><svg class="v-visa vector"><use class="greyed" xlink:href="/w/svg/icon.svg#visa"/></svg></li>
					<li><svg class="v-mastercard vector"><use class="greyed" xlink:href="/w/svg/icon.svg#mastercard"/></svg></li>
					<li><svg class="v-gambleaware vector"><use class="greyed" xlink:href="/w/svg/icon.svg#gambleaware"/></svg></li>
					<li><img src="/w/svg/comodobw.png"/> </li>
					<li><svg class="v-shield vector"><use class="greyed" xlink:href="/w/svg/icon.svg#shield"/></svg> <span class="txt">{{ language.translate('Secure<br>Encryption')}}</span></li>
				</ul>
				<svg class="laurel last"><use class="greyed" xlink:href="/w/svg/icon.svg#laurel"/></svg>
			</div>
		</div>

		<div class="box-extra">
			<div class="cols">
				<div class="col6">
					<div class="box-basic box-quick-play ball">
						<div class="content">
							<h1 class="h2">{{ language.translate("Don't know what to play?") }}</h1>
							<p>{{ language.translate("Use our Lucky Number Generator") }}</p>
							<a href="/{{ lottery }}/play?random" class="btn blue big wide">{{ language.translate("I Feel Lucky!") }}</a>
						</div>
					</div>
				</div>
				<div class="col6">
					<div class="box-basic box-result">
						<div class="cols">
							<div class="col8 content">
								<h1 class="h2">{{ language.translate('EuroMillions Results') }}</h1>
								<p>{{ language.translate('Numbers from') }} {{ last_draw_date }} </p>
								<ul class="no-li inline numbers small">
	                                {% for regular_number in euromillions_results["regular_numbers"] %}
									    <li>{{ regular_number }}</li>
	                                {% endfor %}
	                                {% for lucky_number in euromillions_results["lucky_numbers"] %}
									    <li class="star">{{ lucky_number }}</li>
	                                {% endfor %}
								</ul>
								<a href="/{{ lottery }}/results" class="lnk animate infi"><span class="txt">{{ language.translate('Results &amp; Prizes') }}</span> <svg class="ico v-arrow-right3"><use xlink:href="/w/svg/icon.svg#v-arrow-right3"></use></svg></a>
							</div>
							<div class="col4 woman">&nbsp;</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="informative">
			<div class="who-we-are">



<div class="start-playing">
                                        <div class="cols top">
                                                <div class="col6">
                                                        <div class="title-em cl">
                                                              
                                                        </div>
                                                </div>
                                                <div class="col6">
                                                  
                                                </div>
                                        </div>

                                </div>




				<div class="bg-white">
					<div class="cols fcs">
						<div class="col6 bg-quality"></div>
						<div class="col6 box-txt r">
							<h3 class="li-title">{{ language.translate("Fast") }}</h3>
							<p>{{ language.translate("Players on EuroMillions.com usually pick their lines and make their purchase within two minutes. At Euromillions.com, you just have to choose your favourite numbers, sit back, and relax. We do all the work and even check the results for you. Shortly after the draw, you will receive an email notification detailing the latest results, and if you have won, the winnings will automatically be credited to your player account.") }}</p>

							<h3 class="li-title">{{ language.translate("Convenient") }}</h3>
							<p>{{ language.translate("You can find the tickets you have purchased, the latest euromillions results and customer support in the same place. At only %bet_price% (%bet_price_pound%) per play, we offer the best price available on the Internet. Your winnings are commission free and will remain so forever.
							It doesn’t matter where you live – we are a regulated operator and our services are available worldwide. If you win big, our team of professionals will be at your assistance to make sure you receive your winnings quickly and safely.", ['bet_price': bet_price ,'bet_price_pound': bet_price_pound] ) }}</p>

							<h3 class="li-title">{{ language.translate("Secure") }}</h3>
							<p>{{ language.translate("Euromillions.com is a fully licensed lottery operator. We are customer-centric and constantly endeavour to improve our performance to meet and exceed the demands of our customers. Our partnerships with top financial institutions and certification companies ensures optimum safety when playing on our website.") }}</p>
						</div>
					</div>
					<div class="box-action">
						<span class="h2 phrase">{{ language.translate("Millions of people play EuroMillions everyday") }}</span>
						<a href="{{ lottery }}/play" class="btn big blue">{{ language.translate("Play Anytime, Anywhere") }}</a>
					</div>
				</div>
			</div>

			{% include "index/_top.volt" %}

			<div class="box-basic how-play">
				<div class="cols playing-euro">
					<div class="col6 box-txt l">
						<h2 class="h1 yellow">{{ language.translate("Playing EuroMillions") }}</h2>
						<h3 class="li-title">{{ language.translate("Play") }}</h3>
						<p>{{ language.translate("Select five numbers from 1 to 50 and two lucky stars numbered from 1 to 11. The results are published shortly after the draw on Euromillions.com, and players receive an email notification detailing the latest results and if they have won. Winnings are commission free and are immediately credited to the player’s account on Euromillions.com") }}</p>
						<h3 class="li-title">{{ language.translate("Eligibility") }}</h3>
						<p>{{ language.translate("Any person 18 years or older can play EuroMillions.") }}</p>
						<h3 class="li-title">{{ language.translate("Jackpot") }}</h3>
						<p>{{ language.translate("The EuroMillions Jackpot can reach up to &euro;190 million with a guaranteed jackpot of at least €15 million (&pound;10.5 million) per draw (two draws per week). In the absence of a jackpot winner, the money is rolled over to the next draw and will grow until the jackpot is won, or the Euromillions Pool Cap (&euro;190 million) is reached.") }}</p>
						<h3 class="li-title">{{ language.translate("Odds") }}</h3>
						<p>{{ language.translate("The EuroMillions lottery features 13 different tiers and the odds of winning a EuroMillions prize are 1 in 23. To win the Euromillions jackpot prize, players need to match 5 main numbers from 50, and 2 lucky stars from 11. The odds of hitting the Euromillions Jackpot are 1 in 116,531,800.") }}</p>
					</div>
					<div class="col6 bg-hope"></div>
				</div>
				<div class="box-action">
				<span class="h2 phrase">{{ language.translate("If you're not in, you can't Win!") }}</span>	
				<a href="{{ lottery }}/play" class="btn big blue">{{ language.translate("Beat the odds, Play the Lotto") }}</a>
				</div>
			</div>

			{% include "index/_top.volt" %}

		</div>

	</div>
</main>
{% endblock %}
