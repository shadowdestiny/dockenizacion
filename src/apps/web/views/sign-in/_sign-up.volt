{% if  which_form == 'up' and errors %}
    <div class="box error">
        <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
        <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
    </div>
{% endif %}
<form action="{{ url_signup }}" name="form_notifications" id="sign-up-form" method="post">


    <a class="signin--btn" href="/{{ language.translate("link_signin") }}">Sign In</a>

    <div class="close--btn">
        <div class="close--btn--inner"></div>
    </div>

    <h1 class="title">Sign up to create your euromillions.com account.</h1>
    {{ signupform.render('name', {'class':'input'~form_errors['name']}) }}
    {{ signupform.render('surname', {'class':'input'~form_errors['surname']}) }}
    {{ signupform.render('email', {'class':'input'~form_errors['email']}) }}
    {{ signupform.render('password', {'class':'input'~form_errors['password']}) }}
    {{ signupform.render('confirm_password', {'class':'input'~form_errors['confirm_password']}) }}
    {#<p class="small-txt"><svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg> {{ language.translate("signup_passwordLenght") }}</p>#}
    <div class="title"><svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg> {{ language.translate("signup_passwordLenght") }}</div>

    <div class="selectbox">
        {{ signupform.render('country', {'class':'select'~form_errors['country']}) }}
    </div>

    <div class="cl">
        <input id="goSignUp" type="submit" class="hidden2" />
        {% if signIn.myClass == 'sign-in' %}
            <label for="goSignUp" class="submit btn-theme--big">{{ language.translate("signup_createAccount_btn") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
        {% elseif signIn.myClass == 'cart' %}
            <label for="goSignUp" class="submit btn-theme--big">{{ language.translate("Create account &amp; Play") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
        {% endif %}
    </div>

    <div class="box-extra{% if signIn.myClass == 'cart' %} hidden{% endif %}"><span class="txt">{{ language.translate("signup_accountQuestion") }}</span> <a class="btn gwy" href="javascript:void(0)">{{ language.translate("signup_LogIn_btn") }}</a></div>
</form>

{%  if ga_code is defined %}
<!--start PROD imports
<script src="/w/js/dist/GASignUpAttempt.min.js"></script>
end PROD imports-->
<!--start DEV imports-->
<script src="/w/js/GASignUpAttempt.js"></script>
<!--end DEV imports-->
{% endif %}
