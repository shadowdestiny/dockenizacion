{% if  which_form == 'up' and errors %}
    <div class="box error">
        <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
        <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
    </div>
{% endif %}
{{ form( url_signup  ) }}
    {% if  which_form == 'in' %}
        {% set form_errors['email'] = '' %}
        {% set form_errors['password'] = '' %}
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
        
        {% if signIn.myClass == 'sign-in' %}
            <label for="goSignUp" class="submit btn big blue">{{ language.translate("Connect to a secure server") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
        {% elseif signIn.myClass == 'cart' %}
            <label for="goSignUp" class="submit btn big blue">{{ language.translate("Create account &amp; Play") }} <svg class="ico v-arrow-right"><use xlink:href="/w/svg/icon.svg#v-arrow-right"></use></svg></label>
        {% endif %}
    </div>

    
        <div class="box-extra{% if signIn.myClass == 'cart' %} hidden{% endif %}"><span>{{ language.translate("Do you have an account?") }}</span> <a class="btn gwy" href="javascript:void(0)">{{ language.translate("Log in") }}</a></div>
    
{{ endform() }}