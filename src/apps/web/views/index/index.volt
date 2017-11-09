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
<script src="/w/js/mobileFix.js"></script>
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

		var html_formatted_offset = [];
		$('.countdown .dots').eq(2).hide();
		$('.countdown .seconds').hide();
		var element = $('.countdown');
		var html_formatted = element.html();
		$('.countdown .dots').eq(2).show();
		$('.countdown .seconds').show();
		$('.countdown .day').remove();
		$('.countdown .dots').eq(0).remove();
		html_formatted_offset[0] = $('.countdown').html();
		$('.countdown .hour').remove();
		$('.countdown .dots').eq(0).remove();
		html_formatted_offset[1] = $('.countdown').html();
		$('.countdown .minute').remove();
		$('.countdown .dots').eq(0).remove();
		html_formatted_offset[2] = $('.countdown').html();
		var finish_action = function(){
		$('.box-next-draw .btn.red').remove();
		}
		var date = '{{ date_draw }}'; {#  To test "2015/11/17 10:49:00"  #}
		var finish_text = "<div class='closed'>{{ language.translate('The Draw is closed') }}</div>";
		count_down(element,html_formatted,html_formatted_offset, date,finish_text, finish_action);
    });
{% endblock %}

{% block body %}
<main id="content">
	<div class="large wrapper">
		<div class="banner">
			<div class="box-jackpot">
				<div class="info">{{ language.translate("banner1_head") }}</div>
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
				<a href="/{{ language.translate("link_euromillions_play") }}" class="btn red huge">{{ language.translate("banner1_btn") }}</a>
				<div class="for-only">{{ language.translate("banner1_subbtn")}} {{ bet_price }}</div>
				<div class="for-only" style="font-size: 12px;">{{ language.translate("nextDraw_lbl")}}:
					<span class="countdown">
						<span class="day unit">
							<span class="val">%-d day</span>
						</span>
						<span class="dots"></span>
						<span class="hour unit">
							<span class="val">%-H hrs</span>
						</span>
						<span class="dots"></span>
						<span class="minute unit">
							<span class="val">%-M min</span>
						</span>
						<span class="dots"></span>
						<span class="seconds unit">
							<span class="val">%-S s</span>
						</span>
					</span>
				</div>
			</div>
			<div class="txt">{{ language.translate("banner1_subline") }}</div>
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
					<a href="/{{ language.translate('link_christmas_play') }}">
						<img src="/w/img/home/{{ language.translate('home_christmas_image_billions') }}" border=0 />
					</a>
					{#<div class="box-basic box-quick-play ball">
						<div class="content">
							<h1 class="h2">{{ language.translate("cta1_head") }}</h1>
							<p>{{ language.translate("cta1_text") }}</p>
							<a href="/{{ language.translate("link_euromillions_play") }}?random" class="btn blue big wide">{{ language.translate("cta1_btn") }}</a>
						</div>
					</div>#}
				</div>
				<div class="col6">
					<div class="box-basic box-result">
						<div class="cols">
							<div class="col8 content">
								<h1 class="h2">{{ language.translate("results_head") }}</h1>
								<p>{{ language.translate("results_text") }} {{ last_draw_date }} </p>
								<ul class="no-li inline numbers small">
	                                {% for regular_number in euromillions_results["regular_numbers"] %}
									    <li>{{ regular_number }}</li>
	                                {% endfor %}
	                                {% for lucky_number in euromillions_results["lucky_numbers"] %}
									    <li class="star">{{ lucky_number }}</li>
	                                {% endfor %}
								</ul>
								<a href="/{{ language.translate('link_euromillions_results') }}" class="lnk animate infi"><span class="txt">{{ language.translate("results_link") }}</span> <svg class="ico v-arrow-right3"><use xlink:href="/w/svg/icon.svg#v-arrow-right3"></use></svg></a>
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
							<h3 class="li-title">{{ language.translate("cta2_sub1") }}</h3>
							<p>{{ language.translate("cta2_text1") }}</p>

							<h3 class="li-title">{{ language.translate("cta2_sub2") }}</h3>
							<p>{{ language.translate("cta2_text2", ['bet_price': bet_price ,'bet_price_pound': bet_price_pound] ) }}</p>

							<h3 class="li-title">{{ language.translate("cta2_sub3") }}</h3>
							<p>{{ language.translate("cta2_text3") }}</p>
						</div>
					</div>
					<div class="box-action">
						<span class="h2 phrase">{{ language.translate("cta2_tagline") }}</span>
						<a href="{{ language.translate("link_euromillions_play") }}" class="btn big blue">{{ language.translate("cta2_btn") }}</a>
					</div>
				</div>
			</div>

			{% include "index/_top.volt" %}

			<div class="box-basic how-play">
				<div class="cols playing-euro">
					<div class="col6 box-txt l">
						<h2 class="h1 yellow">{{ language.translate("cta3_head") }}</h2>
						<h3 class="li-title">{{ language.translate("cta3_sub1") }}</h3>
						<p>{{ language.translate("cta3_text1") }}</p>
						<h3 class="li-title">{{ language.translate("cta3_sub2") }}</h3>
						<p>{{ language.translate("cta3_text2") }}</p>
						<h3 class="li-title">{{ language.translate("cta3_sub3") }}</h3>
						<p>{{ language.translate("cta3_text3") }}</p>
						<h3 class="li-title">{{ language.translate("cta3_sub4") }}</h3>
						<p>{{ language.translate("cta3_text4") }}</p>
					</div>
					<div class="col6 bg-hope"></div>
				</div>
				<div class="box-action">
				<span class="h2 phrase">{{ language.translate("cta3_tagline") }}</span>
				<a href="{{ language.translate("link_euromillions_play") }}" class="btn big blue">{{ language.translate("cta3_btn") }}</a>
				</div>
			</div>

			{% include "index/_top.volt" %}

		</div>

	</div>
</main>
{% endblock %}
