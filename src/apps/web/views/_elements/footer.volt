<footer data-role="footer" class="main-foot">
	<div class="wrapper">
		<div class="cols box-links">
			<div class="col20per">
				<strong>{{ language.translate('Play') }}</strong>
				<ul>
					<li><a href="/{{ lottery }}/play">{{ language.translate('EuroMillions') }}</a></li>
{# Future links
					<li><a href="javascript:void(0);">EuroJackpot</a></li>
					<li><a href="javascript:void(0);">MegaMillions</a></li>
					<li><a href="javascript:void(0);">PowerBall</a></li>
#}
				</ul>
			</div>
			<div class="col20per">
				<strong>{{ language.translate('Winning Numbers') }}</strong>
				<ul>
					<li><a href="/{{ lottery }}/results">{{ language.translate('Latest Results') }}</a></li>
					<li><a href="/{{ lottery }}/results/draw-history-page">{{ language.translate('Draw History') }}</a></li>
				</ul>
			</div>
			<div class="col20per">
				<strong>{{ language.translate('Your Account')}}</strong>
				<ul>
					{% if user_logged %}
						<li><a href="/account/games">{{ language.translate('My Tickets') }}</a></li>
						<li><a href="/account/wallet">{{ language.translate('Transactions') }}</a></li>
						<li><a href="/account/wallet">{{ language.translate('Deposit Funds') }}</a></li>
						<li><a href="/account/wallet">{{ language.translate('Withdraw winnings') }}</a></li>
 						<li><a href="/logout">{{ language.translate("Sign out") }}</a></li>
					{% else %}
						<li><a href="/sign-in">{{ language.translate('Log in') }}</a></li>
						<li><a href="/sign-up">{{ language.translate('Sign up') }}</a></li>
					{% endif %}
				</ul>
			</div>
			<div class="col20per">
				<strong>{{ language.translate('Help')}}</strong>
				<ul>
					<li><a href="/{{ lottery }}/help">{{ language.translate('How to Play') }}</a></li>
					<li><a href="/{{ lottery }}/faq">{{ language.translate('Frequently Asked Questions') }}</a></li>
					<li><a href="/contact/">{{ language.translate('Contact us') }}</a></li>
				</ul>
			</div>
			<div class="col20per">
				<strong>{{ language.translate('About')}}</strong>
				<ul>
					<li><a href="">{{ language.translate('News') }}</a></li>
					<li><a href="/#about-us">{{ language.translate('About us') }}</a></li>
					<li><a href="/legal/index">{{ language.translate('Terms &amp; Conditions') }}</a></li>
					<li><a href="/legal/privacy">{{ language.translate('Privacy Policy') }}</a></li>
					<li><a href="/legal/cookies">{{ language.translate('Cookies') }}</a></li>


{# Future links
					<!--<li><a href="javascript:void(0);">Affiliate Program</a></li>-->
#}
				</ul>
			</div>
		</div>
	</div>
	<aside class="logo-social cl">
		<div class="wrapper">
			<div class="social">
				<ul>
					<li class="fb"><a href="https://www.facebook.com/Euromillionscom-204411286236724/"><svg class="ico v-facebook"><use xlink:href="/w/svg/icon.svg#v-facebook"></use></svg><span class="txt">{{ language.translate('Facebook') }}</span></a></li>
					<li class="gp"><a href="https://plus.google.com/+Euromillionscom"><svg class="ico v-google-plus"><use xlink:href="/w/svg/icon.svg#v-google-plus"></use></svg><span class="txt">{{ language.translate('Google +') }}</span></a></li>
					<li class="tw"><a href="https://twitter.com/_lotteries"><svg class="ico v-twitter"><use xlink:href="/w/svg/icon.svg#v-twitter"></use></svg><span class="txt">{{ language.translate('Twitter') }}</span></a></li>
				</ul>
			</div>
			{% include "_elements/logo.volt" %}
		</div>
	</aside>
	<div class="info cl">
		<div class="wrapper">
			<div class="cols">
				<div class="col4 txt">
					{{ language.translate('Material Copyright &copy; 2011 Euromillions.com
					<br>Euromillions is the trademark of Services aux Loteries en Europe') }}
				</div>
				<div class="col8 box-partner">
					<ul class="no-li inline">
						<li><a href="http://www.visaeurope.com/"><svg class="v-visa vector"><use xlink:href="/w/svg/icon.svg#visa"/></svg></a></li>
						<li><a href="http://www.mastercard.com/eur/"><svg class="v-mastercard vector"><use xlink:href="/w/svg/icon.svg#mastercard"/></svg></a></li>
						<li><a href="http://www.gambleaware.co.uk/"><svg class="v-gambleaware vector"><use xlink:href="/w/svg/icon.svg#gambleaware"/></svg></a></li>
						<li><a href="http://www.geotrust.com/"><svg class="v-geotrust vector"><use xlink:href="/w/svg/icon.svg#geotrust"/></svg></a></li>
					</ul>
					{#
					<ul class="no-li inline">
						<li><a href="https://www.mastercard.com/eur/" class="sprite card mastercard"><span class="txt">Mastercard</span></a></li>
						<li><a href="https://www.visaeurope.com/" class="sprite card visa"><span class="txt">Visa</span></a></li>
						<li><a href="https://www.visaeurope.com/" class="sprite card visa-electron"><span class="txt">Visa Electron</span></a></li>
						<li class="fix-margin"><a href="http://www.gambleaware.co.uk/" class="sprite gambleaddict"><span class="txt">Gamble Addict</span></a></li>
						<li><a href="https://www.geotrust.com/" class="sprite geotrust"><span class="txt">GeoTrust</span></a></li>
					</ul>
					#}
				</div>
			</div>
		</div>
	</div>
</footer>
<div class="media"></div> {# Used to check the size of the document to determin what size it is with JS #}
