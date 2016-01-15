{{ form(url_signin) }}
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

        {% if signIn.myClass == 'sign-in' %}
            <label for="go" class="submit btn big blue">{{ language.translate("Log in to a secure server") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
        {% elseif signIn.myClass == 'cart' %}
            <label for="go" class="submit btn big blue">{{ language.translate("Log in &amp; Play") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
        {% endif %}
    </div>
    
    <div class="box-extra{% if signIn.myClass == 'cart' %} hidden{% endif %}"><span>{{ language.translate("Don't you have an account?") }}</span> <a class="btn gwy" href="javascript:void(0)">{{ language.translate("Sign up") }}</a></div>
    
{{ endform() }}