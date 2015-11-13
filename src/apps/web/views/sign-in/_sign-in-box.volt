<main id="content">
    <div class="wrapper">
        <div class="box-sign" data-role="tabs">
            <div class="cl tabs-menu" data-role="navbar">
                <a href="#one" class="login tab{% if which_form == 'in' %} active{% endif %}">
                    <span class="h2">{{ language.translate("Log in") }}</span>
                </a>
                <a href="#two" class="signup tab{% if which_form == 'up' %} active{% endif %}">
                    <span class="h2">{{ language.translate("Sign up") }}</span>
                </a>
            </div>

            <div class="my-left hidden">
                <h1 class="h3">{{ language.translate("Why do I need to sign up?") }}</h1>
                <p>{{ language.translate("By creating an account, we can guarantee a faster process to play your favourite numbers and quicker response time to cash in, if you win the lottery.") }}</p>
            </div>

            <div class="my-right hidden">
                <h1 class="h3">{{ language.translate("We respect your privacy") }}</h1>
                <p>{{ language.translate("We will never post anything without your permission.
                <br>We ask you to connect for a faster sign in process.") }}
                </p>
            </div>

            <div class="wrap">
                <div class="padding">
                    <div class="sign center">
                        <div id="one" class="tab-content{% if which_form == 'in' %} active{% endif %}">
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
                            <div class="cols">
                                <div class="col6">
                                    <label class="label" for="remember">
                                        {{ signinform.render('remember', {'class':'checkbox', 'data-role':'none'}) }}
                                        <span class="txt">{{ language.translate("Stay signed in") }}</span>
                                    </label>
                                </div>
                                <div class="col6 forgot-psw">
                                    <a href="user-access/forgotPassword">{{ language.translate("Forgot password?") }}</a>
                                </div>
                            </div>
                            <div class="cl">
                                <input id="go" type="submit" class="hidden2" />
                                <label for="go" class="submit btn big blue">Log in to a secure server <svg class="ico v-right"><use xlink:href="/w/svg/icon.svg#v-right"></use></svg></label>
                            </div>
                            {{ endform() }}
                        </div>

                        <div id="two" class="tab-content{% if which_form == 'up' %} active{% endif %}">
                            {# DO NOT DELETE - Facebook connect
                            <div class="connect">
                                <a href="#" class="btn blue big"><svg class="ico v-facebook"><use xlink:href="/w/svg/icon.svg#v-facebook"></use></svg> {{ language.translate("Log in with Facebook") }}</a>
                                <a href="#" class="btn red big"><svg class="ico v-google-plus"><use xlink:href="/w/svg/icon.svg#v-google-plus"></use></svg></span> {{ language.translate("Log in with Google") }}</a>
                            </div>

                            <div class="separator">
                                <hr class="hr">
                                <span class="bg-or"><span class="or">or</span></span>
                            </div>
                            #}

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
                            {{ endform() }}
                        </div>

                    </div>
                </div>
            </div>


            <div class="small txt">
                {{ language.translate("By signing in you agree to our") }} <a href="javascript:void(0);">{{ language.translate("Terms &amp; Conditions") }}</a>
                <br>{{ language.translate("and agree that you are 18+ years old") }}
            </div>
        </div>

    </div>
</main>
