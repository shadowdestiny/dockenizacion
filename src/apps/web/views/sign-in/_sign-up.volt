{% if  which_form == 'up' and errors %}
    <div class="box error">
        <svg class="ico v-warning">
            <use xlink:href="/w/svg/icon.svg#v-warning"></use>
        </svg>
        <span class="txt">{% for error in errors %}{{ error }}<br>{% endfor %}</span>
    </div>
{% endif %}
<form action="{{ url_signup }}" onsubmit="fbRegistration()" name="form_notifications" id="sign-up-form" method="post">

    <a class="signin--btn" href="/{{ language.translate("link_signin") }}">Sign In</a>

    <div class="close--btn">
        <div class="close--btn--inner"></div>
    </div>

    {{ signupform.render('name', {'class':'input'~form_errors['name']}) }}
    {{ signupform.render('surname', {'class':'input'~form_errors['surname']}) }}

    <div class="input--email">
        {{ signupform.render('email', {'class':'input'~form_errors['email']}) }}
    </div>
    <div class="input--password">
        {{ signupform.render('password', {'class':'input'~form_errors['password']}) }}
    </div>
    <div class="input--password">
        {{ signupform.render('confirm_password', {'class':'input'~form_errors['confirm_password']}) }}
    </div>
    {#<p class="small-txt"><svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg> {{ language.translate("signup_passwordLenght") }}</p>#}
    <div class="pass-alert">
        <span>
        <svg class="ico v-info">
            <use xlink:href="/w/svg/icon.svg#v-info"></use>
        </svg>
        </span>{{ language.translate("signup_passwordLenght") }}</div>
        <div class="selectTitle">Date of Birth </div>
        <div class="selectbox-container">
          <div class="selectbox three-cols one">
              {{ signupform.render('day', {'class':'select'~form_errors['country']}) }}
          </div>
          <div class="selectbox three-cols two">
              {{ signupform.render('month', {'class':'select'~form_errors['country']}) }}
          </div>
          <div class="selectbox three-cols last-col three">
              {{ signupform.render('year', {'class':'select'~form_errors['country']}) }}
          </div>
        </div>
     <div class="selectTitle">Country of Residence</div>
    <div class="selectbox">
        {{ signupform.render('country', {'class':'select'~form_errors['country']}) }}
    </div>
    <div class="selectTitle">Phone number</div>
    <div class="selectbox-container">
      <div class="selectbox prefix-col">
        {{ signupform.render('prefix', {'class':'select'~form_errors['country']}) }}
      </div>
      <div class="phone-col">
        {{ signupform.render('phone', {'class':'input'~form_errors['name']}) }}
      </div>
    </div>

    <div class="cl txt--accept">
        <label class="label left" for="accept">
        {{ signupform.render('accept', {'data-role':'none','class':'checkbox'~form_errors['accept']}) }}
            <span class="checkbox-after">
            </span>
            <span class="txt">
                {{ language.translate("signup_signInTC") }}
            </span>
        </label>
    </div>

    <div class="cl btn-row">
        <input id="goSignUp" type="submit" class="hidden2"/>
        {% if signIn.myClass == 'sign-in' %}
            <label for="goSignUp" class="submit btn-theme--big">
                <span class="resizeme">{{ language.translate("signup_createAccount_btn") }}</span>
            </label>
        {% elseif signIn.myClass == 'cart' %}
            <label for="goSignUp" class="submit btn-theme--big">
                <span class="resizeme">{{ language.translate("signup_createAccount_btn") }}</span>
            </label>
        {% endif %}
    </div>

    <div class="cl txt--already-have-account">
        {{ language.translate("signup_accountQuestion") }} <a href="/{{ language.translate("link_signin") }}">{{ language.translate("signup_LogIn_btn") }}</a>
    </div>

</form>
<script>
    function fbRegistration() {
        fbq('track', 'CompleteRegistration');
    }
</script>
{% if ga_code is defined %}
    <!--start PROD imports
    <script src="/w/js/dist/GASignUpAttempt.min.js"></script>
    end PROD imports-->
    <!--start DEV imports-->
    <script src="/w/js/GASignUpAttempt.js"></script>
    <!--end DEV imports-->
{% endif %}
