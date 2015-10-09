{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/account.css">
{% endblock %}
{% block bodyClass %}email{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block template_scripts %}
<script>
/* EMTD - This function together with the one in PLAY need to become one single function shared between the two pages */
$(function(){
    $(".threshold").prop('disabled', 'disabled');
    $('#threshold').change(function(){
        if($('#threshold').prop("checked")){
            $(".threshold").prop('disabled', false);
        }else{
            $(".threshold").prop('disabled', 'disabled').show().val("default");
            $(".input-value").hide();
        }
    });

    $(".threshold").change(function(){
        if($(this).val() == 'choose'){
            $(".input-value").show();
            $(this).hide();
        }
    });
});
</script>
{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass": "email"}'|json_decode %}
           {% include "account/_nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title yellow">{{ language.translate("Email Settings") }}</h1>
            <div class="cl">
                <div class="email-me">
                    Email me
                </div>
                <ul class="no-li options">
                    <li>
                        {% include "_elements/jackpot-threshold.volt" %}
                    </li>
                    <li>
                        <label for="check2">
                            <input id="check2" class="checkbox" type="checkbox" checked="checked" data-role="none">
                            When Auto-Play has not enough funds
                        </label>
                    </li>
                    <li>
                        <label for="check3">
                            <input id="check3" class="checkbox" type="checkbox" checked="checked" data-role="none">
                            When Auto-Play has played the last Draw
                        </label>
                    </li>
                    <li>
                        <label for="check4">
                            <input id="check4" class="checkbox" type="checkbox" checked="checked" data-role="none">
                            Results of the Draw
                            <select data-role="none">
                                <option>When I played a ticket</option>
                                <option>Always</option>
                            </select>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</main>
{% endblock %}