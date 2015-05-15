{# EMTD To insert *burger* for  mobile version #}

<header>
	<nav class="top-nav">
		<div class="wrapper">
			<ul class="ul-top-nav">
				<li class="inter">
					<a class="link" href="javascript:void(0);">Euro / English <span class="ico ico-pull-down"></span></a>
					
					<div class="sub-inter arrow-box2">
						<h1 class="h3">Select Regional Settings</h1>
						<div class="box-currency cl">
							<span class="txt">Currency</span> 
							<select>
								<option value="0">&euro; &nbsp;Euro</option>
								<option value="1">currency 2</option>
								<option value="2">currency 3</option>
							</select>
						</div>
						<div class="box-lang cl">
							<span class="txt">Language</span>
							<select>
								<option value="0">English</option>
								<option value="1">language 2</option>
								<option value="2">language 3</option>
							</select>
						</div>
						<a href="javascript:void(0);" class="btn red"><span class="text">Save</span></a>
					</div>
				</li>
				<li class="help">
					<a class="link" href="javascript:void(0);">Help <span class="ico ico-pull-down"></span></a>
					<ul class="sub-help arrow-box">
						<li><a href="javascript:void(0);">How to Play</a></li>
						<li><a href="javascript:void(0);">Frequently Asked Questions</a></li>
						<li><a href="javascript:void(0);">Player Protection</a></li>
						<li><a href="javascript:void(0);">Contact us</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
	<div class="head">
		<div class="wrapper">
			<a href="javascript:void(0);" class="logo" title="Go to Homepage">
				<picture class="pic" alt="EuroMillions.com">
					<!--[if IE 9]><video style="display: none;"><![endif]-->
					<source media="(max-width: 768px)" srcset="/img/logo/euromillions-sm.png">
					<!--[if IE 9]></video><![endif]-->
					<img srcset="/img/logo/euromillions.png">
				</picture>
			</a>
			<nav class="main-nav">
				<ul>
					<li class="active">
						<a href="javascript:void(0);">
							<span class="link">{{ language.translate("Win top prizes") }}</span>
							<br class="br"><span class="txt">{{ language.translate("Play Games") }}
							</span>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);">
							<span class="link">{{ language.translate("Winning") }}
							</span>
							<br class="br"><span class="txt">{{ language.translate("Numbers") }}
							</span>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);">
							<span class="link">{{ language.translate("Hello, Sign in") }}
							</span>
							<br class="br"><span class="txt"><span class="ico ico-user"></span>{{ language.translate(" Your Account") }}
							</span>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);">
							<span class="ico ico-cart"></span>
							<br class="br"><span class="txt">{{ language.translate("Cart") }}
							</span>
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header>