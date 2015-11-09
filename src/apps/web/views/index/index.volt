{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/home.css">{% endblock %}
{% block bodyClass %}home{% endblock %}

{% block header %}
<a id="top"></a>
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
<script>
var checkWinSize = 0;
function checkWin(){
	console.log("media="+ $(".media").width());
	var temp = checkWinSize;
	if($(".media").width() > 1){
		checkWinSize = 1;
	}else{
		checkWinSize = 0;
	}
	if(checkWinSize != temp){
		console.log("different")
		$("#win[style]").removeAttr("style").addClass("fix-modal");
	}
}

$(function(){
	$('.box-prize .time').countdown('{{ date_to_draw }}')
	.on('update.countdown', function(event){
		var format = '%-Hh %-Mm %-Ss';
		if(event.offset.days > 0){
			format = '%-dd ' + format;
		}
		$(this).html(event.strftime(format));
	}).on('finish.countdown', function(event){
		$(this).html("{{language.translate('Draw closed')}}").parent().addClass('disabled');
	});

    $("#win").easyModal({
	    top:100,
	    autoOpen:true,
	    overlayOpacity:0.7,
	    overlayColor:"#000",
	    transitionIn:'animated fadeIn',
	    transitionOut:'animated fadeOut'
    });
    $(window).resize(checkWin); //fix alignment of the popup when resized
});


</script>
{% endblock %}

{% block modal %}
<a href="/account/transaction" id="win" class="modal win">
	<span class="btn-box"><span class="btn blue">View the prize</span></span>
</a>
{% endblock %}

{% block body %}
<main id="content">
	<div class="wrapper">
		<div class="cols">
			<div class="col6 box-left">
				<a href="/play" class="box-estimated no-lnk">
					<div class="content">
						<h1 class="h2">{{ language.translate("Estimated Euromillions Jackpot") }}</h1>
						{% set extraClass='{"boxvalueClass": "yellow","currencyClass":"","valueClass":""}'|json_decode %}
						{% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
						<span class="btn white try animate infi">{{ language.translate("Try your luck") }} <i class="ico ico-arrow-right3"></i></span>
					</div>
					<svg class="vector"><use xlink:href="#emblem"></use></svg>
				</a>
				<div class="box-how">
					<div class="bg-yellow">
						<h1 class="h3">{{ language.translate("How to play") }}</h1>
					</div>
					<ul class="no-li cl">
						<li>
							<svg class="vector"><use xlink:href="#lottery-ticket"></use></svg>

							<div class="box-txt">
								<h2 class="h3"><span class="grey">1.</span> {{ language.translate("play")|upper }}</h2>
								<p class="sub-txt">{{ language.translate("Choose <em>PLAY NOW</em> or <em>QuickPlay</em>") }}</p>
							</div>
						</li>
						<li>
							<svg class="vector"><use xlink:href="#monitor-number"></use></svg>
						
							<div class="box-txt">
								<h2 class="h3"><span class="grey">2.</span> {{ language.translate("pick")|upper }}</h2>
								<p class="sub-txt">{{ language.translate("Pick 5+2 Lucky Stars or <em>QuickPick</em>") }}</p>
							</div>
						</li>
						<li>
							<svg class="vector"><use xlink:href="#winner-cup"></use></svg>

							<div class="box-txt">
								<h2 class="h3"><span class="grey">3.</span> {{ language.translate("win")|upper }}</h2>
								<p class="sub-txt">{{ language.translate("Check results and cash your winnings") }}</p>
							</div>
						</li>
					</ul>
					<div class="center">
						<a href="/play" class="btn big blue">{{ language.translate("Start to Play") }} <i class="ico ico-arrow-right"></i></a>
					</div>
				</div>
				<a href="/numbers" class="box-result no-lnk">
					<div class="cols">
						<div class="col8 content">
							<h1 class="h2">{{ language.translate('Euromillions Results') }}</h1>
							<p>{{ language.translate('Draw of') }} Tuesday, June 16, 2015</p>
							<ul class="no-li inline numbers small">
                                {% for regular_number in euromillions_results["regular_numbers"] %}
								    <li>{{ regular_number }}</li>
                                {% endfor %}
                                {% for lucky_number in euromillions_results["lucky_numbers"] %}
								    <li class="star">{{ lucky_number }}</li>
                                {% endfor %}
							</ul>
							<span class="lnk animate infi"><span class="txt">{{ language.translate('Results &amp; Prizes') }}</span> <i class="ico ico-arrow-right3"></i></span>
						</div>
						<div class="col4 woman">&nbsp;</div>
					</div>
				</a> 
			</div>
			<div class="col6 box-right">

				<div class="box-play">
					<a href="/play" class="outbound">
						<div class="content">
							<p class="h2">{{ language.translate('for only %priceValue% &euro;',['priceValue':2,35]) }}</p>
							<div class="win-millions">
								<picture class="pic">
									<!--[if IE 9]><video style="display: none;"><![endif]-->
									<source media="(max-width: 768px)" srcset="/w/img/home/win-millions-sm.png">
									<!--[if IE 9]></video><![endif]-->
									<img src="w/img/home/win-millions.png" srcset="/w/img/home/win-millions.png, /w/img/home/win-millions@2x.png 1.5x" alt="{{ language.translate('Win Millions') }}">
								</picture>
							</div>
							<div class="box-ball">
								<div class="btn big purple">
									{{ language.translate('PLAY NOW')|upper }} <i class="ico ico-arrow-right"></i>
									<span class="ball"></span>
								</div>
							</div>

							<ul class="no-li awards">
								<li class="best-price"><span class="txt">{{ language.translate('Best price') }}</span></li>
								<li class="risk-free"><span class="txt">{{ language.translate('Risk free') }}</span></li>
							</ul>
						</div>
					</a>
				</div>

				<div class="box-prize">
					<div class="content">
						<div class="bg2">
							<div class="bg">
								<div class="content">
									<div class="cols">
										<div class="col6">
											<h1 class="h2">{{ language.translate('1st Prize') }}</h1>
                                            {% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
                                            {% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
											<div class="box-btn">
												<a href="/play" class="btn blue expand">{{ language.translate('I feel lucky') }} <i class="ico ico-arrow-right"></i></a>

												<a href="/play" class="btn red expand">{{ language.translate('Pick your numbers') }} <i class="ico ico-arrow-right"></i></a>
											</div>
										</div>
										<div class="col6 center box-vector">
											<svg class="vector"><use xlink:href="#lotto-game"></use></svg>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="notes cl">
							<div class="left">
								<i class="ico ico-hourglass"></i>
								<span class="time">
									<span id="clock"></span>
								</span>
								{# EMTD be careful to add singolar/plural: DAY / DAYS and if it is a matter of hours put content text as "04:09:34" format#}
							</div>
							<div class="right">
								{{ language.translate('Luck is not as random as you think') }}
							</div>
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
								<svg class="vector best"><use xlink:href="#best-choice"></use></svg>
								<svg class="vector text"><use xlink:href="#em-golden"></use></svg>
								<h3 class="title">Fast, Convenient &amp; Secure</h3>
							</div>
						</div>
						<div class="col6">
							<span class="gold-pile">
								<svg class="vector"><use xlink:href="#logo-shadow"></use></svg>
								<a href="/play" class="btn blue">Start playing, Win millions</a>
							</span>
						</div>
					</div>
				</div>
				<div class="bg-white">
					<div class="cols fcs">
						<div class="col6 bg-quality"></div>
						<div class="col6 box-txt r">
							<h3 class="li-title">Fast</h3>
							<p>Regular players rarely exceed two minutes to validate their euromillions tickets on our website. At Euromillions.com, you just have to choose your favourite numbers, sit back, and relax. We do all the nerve-wracking, waiting, and checking the results for you. Shortly after the draw, you will receive an email notification detailing the latest results, and if you have won, the winnings will automatically be credited to your player account.</p>

							<h3 class="li-title">Convenient</h3>
							<p>You’ll find your played tickets, latest euromillions results, and customer support at the same place. At only &euro; 2.35 (&pound; 1.65) per play, we offer the best price available on the Internet. Your winnings are commission free and will remain so forever.
							It doesn’t matter where you live – we are a regulated operator and our services are available worldwide. If you win big, our team of professionals will be at your assistance to make sure you receive your winnings quickly and safely.</p>

							<h3 class="li-title">Secure</h3>
							<p>Euromillions.com is a fully licensed lottery operator. We are customer-centric and constantly endeavour to improve our performance to meet and exceed the demands of our customers. Our partnerships with top financial institutions and certification companies ensure your optimum safety when playing on our website supporting 256-bit SSL encryption.</p>
						</div>
					</div>
					<div class="box-action">
						<span class="h2 phrase">Millions of people play the Euromillion lottery everyday.</span>
						<a href="/play" class="btn blue">Play anytime, anywhere</a>
					</div>
				</div>
			</div>

			<div class="back-top cl">
				<a data-ajax="false" href="#top"><i class="ico ico-arrow-up2"></i> Back to Top</a>
			</div>

			<div class="box-basic how-play">
				<div class="cols playing-euro">
					<div class="col6 box-txt l">
						<h2 class="h1 yellow">Playing euromillions</h2>
						<h3 class="li-title">Play</h3>
						<p>Players select five main numbers from 1 to 50 and two lucky stars numbered from 1 to 11. The results are published shortly after the draw on Euromillions.com, and players receive an email notification detailing the latest results and if they have won. Winnings are commission free and are immediately credited to the player’s account on Euromillions.com</p>
						<h3 class="li-title">Eligibility</h3>
						<p>Any person who is 18 years or above can participate in the Euromillions. This differs for some countries such as the United Kingdom, where lottery players can participate starting from the age of 16.</p>
						<h3 class="li-title">Jackpot</h3>
						<p>Euromillions jackpot prizes can reach up to &euro; 190 million with a guaranteed jackpot of at least €15 million (&pound; 10.5 million) per draw (two draws per week). In the absence of a first prize winner, the money is rolled over to the next draw which will grow in successive categories until either a Euromillions jackpot winner is produced, or the Euromillions Pool Cap (&euro; 190 million) is reached.</p>
						<h3 class="li-title">Odds</h3>
						<p>To win the Euromillions jackpot prize, players need to match 5 main numbers from 50, and 2 lucky stars from 11. The Euromillions odds of this actually happening are 1 in 116,531,800. In addition, the Euromillions lottery features 13 different tiers and the odds of a Euromillions win are 1 in 23.</p>
					</div>
					<div class="col6 bg-hope"></div>
				</div>
				<div class="box-action">
					<span class="h2 phrase">For a very small amount of money<br class="mobile"> you might change your life.</span>
					<a href="/play" class="btn blue">Beat the odds, Play the Lotto</a>
				</div>
			</div>

			<div class="back-top cl">
				<a data-ajax="false" href="#top"><i class="ico ico-arrow-up2"></i> Back to Top</a>
			</div>

			<div class="box-basic about-us">
				<div class="cols">
					<div class="col6 bg-win"></div>
					<div class="col6 box-txt r">
						<h2 class="h1 yellow">About us</h2>
						<h3 class="li-title">What we do</h3>
						<p>Euromillions.com is the first lottery based website built to work on every device and every screen size, no matter how large or small. Mobile or desktop, we will always offer you the best user experience.</p>

						<p>Your time is valuable to us, so we work hard to provide you with a quick, smart, and reliable experience to play lottery online from the comfort of your home or on the go.</p> 

						<p>Your fate-changer might be right here in the palm of your hand.</p>

						<p>We understand what you expect from us and we assure you that your winnings are commission free and will remain so forever.</p>

						<h3 class="li-title">Who we are</h3>
						<p>Euromillions.com is the first European transnational lottery launched in 2004.</p>

						<p>We are an international team composed of experts and passionate players, and we believe that in order to provide you the best services, we need to follow three important principles: to be fast, convenient and secure.</p> 

						<p>We really hope that playing with us will make your dreams come true. For the less lucky ones, we hope that the thrill of imagining a life with a big lottery prize will give you some pleasant hours of day dreaming, until the day that you actually win and everything that you imagined becomes real.</p>

						<p>Draws are held every Tuesday and Friday night at 20:45 CET in Paris, France.</p>

						<p>We wish you the very best of luck playing and to never stop dreaming.</p>
					</div>
				</div>
				<div class="box-action">
					<span class="h2 phrase">Winning starts by saying to yourself,<br class="mobile"> "One day I’m going to win."</span>
					<a href="/play}"  class="btn blue">Play. Dare to dream. Win.</a>
				</div>
			</div>

			<div class="back-top cl">
				<a data-ajax="false" href="#top"><i class="ico ico-arrow-up2"></i> Back to Top</a>
			</div>
		</div>

	</div>
</main>
{% endblock %}