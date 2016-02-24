{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/cart.css">{% endblock %}
{% block template_scripts_code %}
var size = checkSize();
function swap(myVar){
    $(myVar).click(function(event){
        event.preventDefault();
        $(".col4, .col8").toggle();
    });
}


function hide_forms_sign() {
    alert(size);
    var which_form = '<?php echo $which_form;?>';
    if(which_form == 'in'){
        $(".col8").hide();
    } else {
        $(".col4").hide();
    }
    swap(".col4 .box-extra a, .col8 .box-extra a");
}
function show_forms_sign() {
    if(size >= 1) {
        $(".col8").show();
        $(".col4").show();
    }
    if(size >= 3){
        hide_forms_sign();
    }
}

$(function(){
    if(size >= 3){
        hide_forms_sign();
    }
});

{% endblock %}
{% block bodyClass %}cart profile minimal sign-in{% endblock %}

{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
{% set signIn='{"myClass": "cart"}'|json_decode %}
<main id="content">
    <div class="wrapper">
        <div class="what-user">
            <div class="cols">
                <div class="col4">
                    <div class="box-basic log-in">
                        <div class="info">
                            <div class="txt1">{{ language.translate("Returning user?") }}</div>
                            <div class="txt2 yellow">
                                <div class="line-sep"></div>
                                <div class="line-txt">{{ language.translate("Log in") }}</div>
                            </div>
                        </div>
                        {% set url_signin = '/cart/login' %}
                        {% include "sign-in/_log-in.volt" %}
                    </div>
                </div>
                <div class="col8">
                    <div class="box-basic sign-in">
                        <div class="info">
                            <div class="txt1">{{ language.translate("New to Euromillion?") }}</div>
                            <div class="txt2 yellow">
                                <div class="line-sep"></div>
                                <div class="line-txt">{{ language.translate("Sign Up") }}</div>
                            </div>
                            {% set url_signup = '/cart/profile' %}
                            {% include "sign-in/_sign-up.volt" %}
                        </div>
                    </div>
                </div>
            </div>

            <div class="terms txt">
                {{ language.translate("By signing in you agree to our") }} <a href="/legal/index">{{ language.translate("Terms &amp; Conditions") }}</a>
                <br>{{ language.translate("and that you are 18+ years old.") }}
            </div>

        </div>
    </div>
</main>
{% endblock %}