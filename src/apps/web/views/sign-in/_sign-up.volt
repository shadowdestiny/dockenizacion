{% if  which_form == 'up' and errors %}
    <div class="box error">
        <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
        <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
    </div>
{% endif %}
<form action="{{ url_signup }}" name="form_notifications" id="form-email-settings" method="post">

    {{ signupform.render('name', {'class':'input'~form_errors['name']}) }}
    {{ signupform.render('surname', {'class':'input'~form_errors['surname']}) }}
    {{ signupform.render('email', {'class':'input'~form_errors['email']}) }}
    <div class="info-psw cl">
        <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"/></svg>
        <span class="txt">{{ language.translate("Password must be at least 8 letters long.<br>Composed at least with 1 uppercase and number.") }}</span>
    </div>
    {{ signupform.render('password', {'class':'input'~form_errors['password']}) }}
    {{ signupform.render('confirm_password', {'class':'input'~form_errors['confirm_password']}) }}
    {{ signupform.render('country', {'class':'select'~form_errors['country']}) }}

    <div class="cl">
        <input id="goSignUp" type="submit" class="hidden2" />
        {% if signIn.myClass == 'sign-in' %}
            <label for="goSignUp" class="submit btn big blue">{{ language.translate("Connect to a secure server") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
        {% elseif signIn.myClass == 'cart' %}
            <label for="goSignUp" class="submit btn big blue">{{ language.translate("Create account &amp; Play") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
        {% endif %}
    </div>

    <div class="box-extra{% if signIn.myClass == 'cart' %} hidden{% endif %}"><span class="txt">{{ language.translate("Do you have an account?") }}</span> <a class="btn gwy" href="javascript:void(0)">{{ language.translate("Log in") }}</a></div>
</form>