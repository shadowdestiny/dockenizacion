{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/sign-in.css">{% endblock %}
{% block bodyClass %}sign-in minimal{% endblock %}

{% block template_scripts %}
<script>
function swap(myVar){
    $(myVar).click(function(event){
        event.preventDefault();
        $(".log-in, .sign-up").toggle();
    });
}

$(function(){
    swap(".log-in .box-extra a, .sign-up .box-extra a");
});
</script>
{% endblock %}

{% block body %}
<main id="content" style="padding-top:40px;">
    <div class="wrapper cl">
        <div class="col-left">
            <img class="v-logo vector" alt="Euromillions" src="/w/svg/logo.svg">
        </div>
        <div class="col-right">
            <div class="box-basic log-in">
                <h1 class="h2 title">{{ language.translate("Log in") }}</h1>
     
                {# DO NOT DELETE - Facebook connect
                <div class="connect">
                    <a href="#" class="btn blue big"><svg class="ico v-facebook"><use xlink:href="/w/svg/icon.svg#v-facebook"></use></svg> {{ language.translate("Log in with Facebook") }}</a>
                    <a href="#" class="btn red big"><svg class="ico v-google-plus"><use xlink:href="/w/svg/icon.svg#v-google-plus"></use></svg></span> {{ language.translate("Log in with Google") }}</a>
                </div>

                <div class="separator">
                    <hr class="hr">
                    <span class="bg-or"><span class="or">{{ language.translate("or") }}</span></span>
                </div>
                #}

                {{ form('userAccess/signIn') }}
                    {% if  which_form == 'in' and errors %}
                        <div class="box error">
                            <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
                            <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
                        </div>
                    {% endif %}
                    {{ signinform.render('email', {'class':'input'~form_errors['email']}) }}
                    {{ signinform.render('password', {'class':'input'~form_errors['password']}) }}
                    {{ signinform.render('csrf', ['value': security.getSessionToken()]) }}
                    {{ signinform.render('controller', ['value': controller]) }}
                    {{ signinform.render('action', ['value': action]) }}
                    {{ signinform.render('params', ['value': params]) }}
                    <div class="cl reduce">
                        <label class="label left" for="remember">
                            {{ signinform.render('remember', {'class':'checkbox', 'data-role':'none'}) }}
                            <span class="txt">{{ language.translate("Stay signed in") }}</span>
                        </label>
                        <div class="right forgot-psw">
                            <a href="/user-access/forgotPassword">{{ language.translate("Forgot password?") }}</a>
                        </div>
                    </div>
                    <div class="cl">
                        <input id="go" type="submit" class="hidden2" />
                        <label for="go" class="submit btn big blue">Log in to a secure server <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
                        <div class="box-extra"><span>{{ language.translate("Don't you have an account?") }}</span> <a class="btn gwy" href="javascript:void(0)">{{ language.translate("Sign up") }}</a></div>
                    </div>
                {{ endform() }}
            </div>

            <div class="box-basic sign-up hidden">
                <h1 class="h2 title">{{ language.translate("Sign up") }}</h1>

                {{ form('userAccess/signUp') }}
                    {% if  which_form == 'up' and errors %}
                        <div class="box error">
                            <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
                            <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
                        </div>
                    {% endif %}
                    {{ signupform.render('name', {'class':'input'~form_errors['name']}) }}
                    {{ signupform.render('surname', {'class':'input'~form_errors['surname']}) }}
                    {{ signupform.render('email', {'class':'input'~form_errors['email']}) }}
                    {{ signupform.render('password', {'class':'input'~form_errors['password']}) }}
                    {{ signupform.render('confirm_password', {'class':'input'~form_errors['confirm_password']}) }}
                    {{ signupform.render('country', {'class':'select'~form_errors['country']}) }}
                    {{ signinform.render('controller', ['value': controller]) }}
                    {{ signinform.render('action', ['value': action]) }}
                    {{ signinform.render('params', ['value': params]) }}

                    <div class="cl">
                        <input id="goSignUp" type="submit" class="hidden2" />
                        <label for="goSignUp" class="submit btn big blue">Connect to a secure server <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
                    </div>
                    <div class="box-extra"><span>{{ language.translate("Do you have an account?") }}</span> <a class="btn gwy" href="javascript:void(0)">{{ language.translate("Log in") }}</a></div>
                {{ endform() }}
            </div>


            <div class="terms txt">
                {{ language.translate("By signing in you agree to our") }} <a href="/legal/index">{{ language.translate("Terms &amp; Conditions") }}</a>
                <br>{{ language.translate("and agree that you are 18+ years old") }}
            </div>

        </div>

    </div>
</main>


{% endblock %}

