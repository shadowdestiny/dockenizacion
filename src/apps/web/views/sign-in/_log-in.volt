{% if  which_form == 'in' and errors %}
    <div class="box error">
        <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
        <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
    </div>
{% endif %}
<form action="{{ url_signin }}" name="form_notifications" id="sign-in-form" method="post">
    {{ signinform.render('email', {'class':'input'~form_errors_login['email']}) }}
    {{ signinform.render('password', {'class':'input'~form_errors_login['password']}) }}
    {{ signinform.render('csrf', ['value': security.getSessionToken()]) }}
    <div class="cl reduce">
        <label class="label left" for="remember">
            {{ signinform.render('remember', {'class':'checkbox', 'data-role':'none'}) }}
            <span class="checkbox-after"></span>
            <span class="txt">{{ language.translate("signin_staysigned") }}</span>
        </label>
        <div class="right forgot-psw">
            <a href="/{{ language.translate("link_forgotpw") }}">{{ language.translate("signin_forgotpass") }}</a>
        </div>
    </div>
    
    <div class="cl">
        <input id="go" type="submit" class="hidden2" />
        {% if signIn.myClass == 'sign-in' %}

            <div class="submit-row">
            <label for="go" class="submit  btn-theme--big">
                {{ language.translate("signin_LogIn_btn") }}
                {#<svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg>#}
            </label>
            </div>
        {% elseif signIn.myClass == 'cart' %}
        <div class="submit-row">
            <label for="go" class="submit btn-theme--big">
                {{ language.translate("signin_LogIn_btn") }}
                {#<svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg>#}
            </label>
        </div>
        {% endif %}
    </div>
    
    <div class="box-extra{% if signIn.myClass == 'cart' %} hidden{% endif %}">
        <span class="txt">{{ language.translate("signin_accountQuestion") }}</span>
        <a class="btn gwy" href="/{{ language.translate("link_signup") }}">{{ language.translate("signin_signup_btn") }}</a>
    </div>
</form>
