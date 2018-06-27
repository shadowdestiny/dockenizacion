{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/cart.css">{% endblock %}
{% block template_scripts_code %}

var which_form = '<?php echo $which_form;?>';

function swap(myVar){
    $(myVar).click(function(event){
        event.preventDefault();
        var elem = $(this).parent().parent().parent().parent().parent();
        var isActive = elem.hasClass('active');
        if(isActive){
            $(".what-user .col4").addClass("active").css('display','block');
            $(".what-user .col8").removeClass("active").hide();
            which_form = 'in';
        }else{
            $(".what-user .col8").addClass("active").css('display','block');
            $(".what-user .col4").removeClass("active").hide();
            which_form = 'up';
        }
    });
}

function hide_forms_sign(){
    var size = checkSize();
    if(size >= 3){
        if(which_form == 'in'){
            $(".what-user .col4").addClass('active').css('display','block');
            $(".what-user .col8").removeClass("active").hide();
        }else{
            $(".what-user .col8").addClass('active').css('display','block');
            $(".what-user .col4").removeClass("active").hide();
        }
    }else{
        $(".what-user .col4,.what-user .col8").css('display','table-cell');
        $(".what-user .col8").addClass("active");
        $(".what-user .col4").removeClass("active");
        which_form = 'up';
    }
}
function show_forms_sign(){
    if(size >= 1) {
        $(".what-user .col4,.what-user .col8").css('display','table-cell');
    }
    if(size >= 3){
       hide_forms_sign();
    }
}

$(function(){
    hide_forms_sign();
    $(window).resize(hide_forms_sign);
    swap(".what-user .box-extra a");
});

{% endblock %}
{% block template_scripts_after %}<script src="/w/js/react/tooltip.js"></script>{% endblock %}
{% block bodyClass %}cart profile minimal sign-in{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "profile"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}

{#TODO: uncomment this block if it needed#}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
{% set signIn='{"myClass": "cart"}'|json_decode %}
<main id="content">
    <div class="wrapper">
        <div class="what-user">
            <div class="cols cart-sign-cols">
                {#<div class="col4">#}
                    <div class="signin-form log-in">
                        <div class="info">
                            {#<div class="txt1">{{ language.translate("Returning user?") }}</div>#}
                            {#<div class="txt2 yellow">#}
                                {#<div class="line-sep"></div>#}
                                {#<div class="line-txt">{{ language.translate("Log in") }}</div>#}
                            {#</div>#}
                        </div>
                        {% set url_signin = '/powerball/cart/login' %}
                        {% include "sign-in/_log-in.volt" %}
                    </div>
                {#</div>#}
                {#<div class="col8 active">#}
                    <div class="signin-form sign-in">
                        <div class="info">
                            {#<div class="txt1">{{ language.translate("New to EuroMillions?") }}</div>#}
                            {#<div class="txt2 yellow">#}
                                {#<div class="line-sep"></div>#}
                                {#<div class="line-txt">{{ language.translate("Sign Up") }}</div>#}
                            {#</div>#}
                            {% set url_signup = '/powerball/cart/profile' %}
                            {% include "sign-in/_sign-up.volt" %}
                        </div>
                    </div>
                {#</div>#}
            </div>

            {#<div class="terms txt">#}
                {#{{ language.translate("By signing in you agree to our") }} <a href="/{{ language.translate("link_legal_index") }}">{{ language.translate("Terms &amp; Conditions") }}</a>#}
                {#<br>{{ language.translate("and that you are 18+ years old.") }}#}
            {#</div>#}

        </div>
    </div>
</main>
{% endblock %}
