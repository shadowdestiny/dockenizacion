<footer data-role="footer" class="main-foot">
	<div class="wrapper">
		<div class="cols box-links">
			<div class="col20per">
				<strong>{{ language.translate('Play Games') }}</strong>
				<ul>
					<li><a href="javascript:void(0);">{{ language.translate('EuroMillions') }}</a></li>
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
					<li><a href="javascript:void(0);">{{ language.translate('Latest Results') }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('Last %number% Drawings', ['number': 25]) }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('Draw History') }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('Unclaimed Prizes') }}</a></li>
				</ul>
			</div>
			<div class="col20per">
				<strong>Your Account</strong>
				<ul>
					<li><a href="javascript:void(0);">{{ language.translate('Sign in') }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('My Games') }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('Transactions') }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('Deposit Funds') }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('Withdraw winnings') }}</a></li>
				</ul>
			</div>
			<div class="col20per">
				<strong>Help</strong>
				<ul>
					<li><a href="javascript:void(0);">{{ language.translate('How to Play') }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('Frequently Asked Questions') }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('Player Protection') }}</a></li>
					<li><a href="/contact/guest"{% if user_logged %}{{ 'registered' }}{% else %}{{ 'guest' }}{%  endif %}>{{ language.translate('Contact us') }}</a></li>
				</ul>
			</div>
			<div class="col20per">
				<strong>About</strong>
				<ul>
					<li><a href="javascript:void(0);">{{ language.translate('News') }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('About us') }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('Terms &amp; Conditions') }}</a></li>
					<li><a href="javascript:void(0);">{{ language.translate('Legal info') }}</a></li>
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
					<li class="fb"><a href="javascript:void(0);"><i class="ico ico-facebook"></i><span class="txt">{{ language.translate('Facebook') }}</span></a></li>
					<li class="gp"><a href="javascript:void(0);"><i class="ico ico-google-plus"></i><span class="txt">{{ language.translate('Google +') }}</span></a></li>
					<li class="tw"><a href="javascript:void(0);"><i class="ico ico-twitter"></i><span class="txt">{{ language.translate('Twitter') }}</span></a></li>
				</ul>
			</div>
			<a href="javascript:void(0);" class="small-logo" title="Go to Homepage">
				<picture class="pic">
					<!--[if IE 9]><video style="display: none;"><![endif]-->
					<source media="(max-width: 768px)" srcset="/img/logo/euromillions-sm.png">
					<!--[if IE 9]></video><![endif]-->
					<img src="/img/logo/euromillions.png" alt="{{ language.translate('EuroMillions.com') }}">
				</picture>
			</a>
		</div>
	</aside>
	<div class="info cl">
		<div class="wrapper">
			<div class="cols">
				<div class="col4 txt">
					{{ language.translate('Material Copyright &copy; 2011 Euromillions.com
					<br>Euromillions is the trademark of Services aux Loteries en Europe') }}
				</div>
				<div class="col8 partner">
					<ul class="no-li inline">
						<li><a href="https://www.mastercard.com/eur/" class="sprite card mastercard"><span class="txt">Mastercard</span></a></li>
						<li><a href="https://www.visaeurope.com/" class="sprite card visa"><span class="txt">Visa</span></a></li>
						<li><a href="https://www.visaeurope.com/" class="sprite card visa-electron"><span class="txt">Visa Electron</span></a></li>
						<li class="fix-margin"><a href="http://www.gambleaware.co.uk/" class="sprite gambleaddict"><span class="txt">Gamble Addict</span></a></li>
						<li><a href="https://www.geotrust.com/" class="sprite geotrust"><span class="txt">GeoTrust</span></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</footer>
<div class="media"></div>