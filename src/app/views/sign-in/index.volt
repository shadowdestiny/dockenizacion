{% extends "main.volt" %}
{% block template_css %}
<link rel="stylesheet" href="/css/sign-in.css">
{% endblock %}
{% block template_scripts %}
<script>
var link = "";
function showInfo(){
	if(checkSize() < 2){
	    $(".signup").hover(function(event){
	    	$(".my-left").fadeIn();
	    }, function(){
			$(".my-left").fadeOut();
	    });

	    $(".connect").hover(function(event){
	    	$(".my-right").fadeIn();
	    }, function(){
			$(".my-right").fadeOut();
	    });
	}else{
		$(".signup, .connect").unbind('mouseenter mouseleave');
	}
}
$(function(){
    $(".tabs-menu a").click(function(event){
        event.preventDefault();
		if(!$(this).hasClass("active")){
	        $(this).siblings().removeClass("active");
	        $(this).addClass("active");
	        var tab = $(this).attr("href");
	        $(".tab-content").not(tab).css("display", "none");
	        $(tab).toggle();
		}
        link = this;
    });
    showInfo();
	$(window).resize(showInfo);
});
</script>
{% endblock %}
{% block bodyClass %}sign-in{% endblock %}

{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
	<div class="wrapper">
		<div class="box-sign" data-role="tabs">
			<div class="cols tabs-menu" data-role="navbar">
				<a href="#one" class="signup col6 btn gwy tab">
					<p>{{ language.translate("New user") }}</p>
					<span class="h3">{{ language.translate("Sign up") }}</span>
				</a>
				<a href="#two" class="login col6 btn gwy tab active">
					<p>{{ language.translate("Returning customer") }}</p>
					<span class="h3">{{ language.translate("Log in") }}</span>
				</a>
			</div>

			<div class="my-left hidden">
				<h1 class="h3">Why do I need to sign up?</h1>
				<p>By creating an account, we can guarantee a faster process to play your favourite numbers and quicker response time to cash in, if you win the lottery.</p>
			</div>

			<div class="my-right hidden">
				<h1 class="h3">We respect your privacy</h1>
				<p>
					We will never post anything without your permission.
					<br>We ask you to connect for a faster sign in process.
				</p>
			</div>

			<div class="wrap">
				<div class="padding">
					<div class="sign center">
						<div id="one" class="tab-content">
							<h1 class="h1">{{ language.translate("Sign up") }}</h1>
							<p><em>{{ language.translate("Welcome to Euromillions, let's play!") }}</em></p>

							<div class="connect">
								<a href="#" class="btn blue big"><span class="ico ico-facebook"></span> {{ language.translate("Connect with Faceboo") }}k</a>
								<a href="#" class="btn red big"><span class="ico ico-google-plus"></span> {{ language.translate("Connect with Google") }}</a>
							</div>

							<div class="separator">
								<hr class="hr">
								<span class="bg-or"><span class="or">or</span></span>
							</div>

							<form novalidate>
								<div class="box error">
									<span class="ico-warning ico"></span>
									<span class="txt">Lorem ipsum error aliqua docet lorem ipsum aliqua aliqua docet lorem ipsum aliqua</span>
								</div>

								<input class="input error" type="text" placeholder="Name">
								<input class="input" type="text" placeholder="Surname">
								<input class="input" type="email" placeholder="Email">
								<input class="input" type="password" placeholder="Password">

								<select class="select">
									<option>{{ language.translate("Select your country of residence") }}</option>
									<option>Spain</option>
									<option>Italy</option>
									<option>France</option>
								</select>
								<div class="cl">
									<a href="javascript:void(0);" class="submit btn big blue">{{ language.translate("Connect to a secure server") }} <span class="ico ico-arrow-right"></span></a>
								</div>
							</form>				
						</div>
						<div id="two" class="tab-content active">
							<h1 class="h1">{{ language.translate("Log in") }}</h1>
							<p><em>{{ language.translate("Welcome back, let's play again!") }}</em></p>

							<div class="connect">
								<a href="#" class="btn blue big"><span class="ico ico-facebook"></span> {{ language.translate("Log in with Facebook") }}</a>
								<a href="#" class="btn red big"><span class="ico ico-google-plus"></span> {{ language.translate("Log in with Google") }}</a>
							</div>

							<div class="separator">
								<hr class="hr">
								<span class="bg-or"><span class="or">{{ language.translate("or") }}</span></span>
							</div>

							<form novalidate>
								<input class="input" type="email" placeholder="Email">
								<input class="input" type="password" placeholder="Password">
								<div class="cols">
									<div class="col6">
										<label class="label" for="remember">
											<input id="remember" class="checkbox" type="checkbox" data-role="none">
											<span class="txt">{{ language.translate("Stay signed in") }}</span>
										</label>
									</div>
									<div class="col6 forgot-psw">
										<a href="javascript:void(0);">{{ language.translate("Forgot password?") }}</a>
									</div>
								</div>
								<div class="cl">
									<a href="javascript:void(0);" class="submit btn big blue">{{ language.translate("Log in to a secure server") }} <span class="ico ico-arrow-right"></span></a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>


			<div class="small txt">
				{{ language.translate("By signin in you agree to our") }} <a href="javascript:void(0);">{{ language.translate("Terms &amp; Conditions") }}</a>
				<br>{{ language.translate("and agree that you are 18+ years old") }}
			</div>
		</div>
<!--
			<div class="col4">
				<div class="side-txt last hidden">
					<h1 class="h3">Log in</h1>
					<p>Info login, lorem ipsum</p>
				</div>
			</div>
		</div>
-->
	</div>
</main>

{% endblock %}

