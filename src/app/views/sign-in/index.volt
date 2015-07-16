{% extends "main.volt" %}
{% block template_css %}
<link rel="stylesheet" href="/css/sign-in.css">
{% endblock %}
{% block template_scripts %}
<script>
var link = "";
$(document).ready(function(){
    $(".tabs-menu a").click(function(event) {
    	console.log("1# link= "+link);
        event.preventDefault();
		if(!$(this).hasClass("active")){
	    	console.log("3# link= "+link);
	        $(this).siblings().removeClass("active");
	        $(this).addClass("active");
	        var tab = $(this).attr("href");
	        $(".tab-content").not(tab).css("display", "none");
	        $(tab).toggle();
		}else{

		}
        link = this;
    	console.log("2# link= "+link);
    });
});
</script>
{% endblock %}
{% block bodyClass %}sign-in{% endblock %}

{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
	<div class="wrapper">
<!--
		<div class="cols">
			<div class="col4">
				<div class="side-txt right first hidden">
					<h1 class="h3">Why do I need to sign up?</h1>
					<p>By creating an account, we can guarantee a faster process to play your favourite numbers and quicker response time to cash in, if you win the lottery.</p>
				</div>
			</div>
-->
		<div class="box-sign" data-role="tabs">
			<div class="cols tabs-menu" data-role="navbar">
				<a href="#one" class="col6 btn gwy tab">
					<p>New user</p>
					<span class="h3">Sign up</span>
				</a>
				<a href="#two" class="col6 btn gwy tab active">
					<p>Returning customer</p>
					<span class="h3">Log in</span>
				</a>
			</div>

			<div class="wrap">
				<div class="padding">
					<div class="sign center">
						<div id="one" class="tab-content">
							<h1 class="h1">Sign up</h1>
							<p><em>Welcome to Euromillions, let's play!</em></p>

							<div class="connect">
								<a href="#" class="btn blue big"><span class="ico ico-facebook"></span> Connect with Facebook</a>
								<a href="#" class="btn red big"><span class="ico ico-google-plus"></span> Connect with Google</a>
							</div>

							<div class="separator">
								<hr class="hr">
								<span class="bg-or"><span class="or">or</span></span>
							</div>

							<form novalidate>
								<div class="box error">
									<span class="ico-warning ico"></span>
									<span class="txt">Lorem ipsum error</span>
								</div>

								<input class="input error" type="text" placeholder="Name">
								<input class="input" type="text" placeholder="Surname">
								<input class="input" type="email" placeholder="Email">
								<input class="input" type="password" placeholder="Password">

								<select class="select">
									<option>Select your country of residence</option>
									<option>Spain</option>
									<option>Italy</option>
									<option>France</option>
								</select>
								<div class="cl">
									<a href="javascript:void(0);" class="submit btn big blue">Connect to a secure server <span class="ico ico-arrow-right"></span></a>
								</div>
							</form>				
						</div>
						<div id="two" class="tab-content active">
							<h1 class="h1">Log in</h1>
							<p><em>Welcome back, let's play again!</em></p>

							<div class="connect">
								<a href="#" class="btn blue big"><span class="ico ico-facebook"></span> Log in with Facebook</a>
								<a href="#" class="btn red big"><span class="ico ico-google-plus"></span> Log in with Google</a>
							</div>

							<div class="separator">
								<hr class="hr">
								<span class="bg-or"><span class="or">or</span></span>
							</div>

							<form novalidate>
								<input class="input" type="email" placeholder="Email">
								<input class="input" type="password" placeholder="Password">
								<div class="cols">
									<div class="col6">
										<label class="label" for="remember">
											<input id="remember" class="checkbox" type="checkbox" data-role="none">
											<span class="txt">Stay signed in</span>
										</label>
									</div>
									<div class="col6 forgot-psw">
										<a href="javascript:void(0);">Forgot password?</a>
									</div>
								</div>
								<div class="cl">
									<a href="javascript:void(0);" class="submit btn big blue">Log in to a secure server <span class="ico ico-arrow-right"></span></a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<div class="small txt">
				By signin in you agree to our <a href="javascript:void(0);">Terms &amp; Conditions</a>
				<br>and agree that you are 18+ years old
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

