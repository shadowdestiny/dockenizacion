{% extends "main.volt" %}
{% block template_css %}{% endblock %}
{% block bodyClass %}recovery minimal{% endblock %}

{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block template_css %}
<style>
.title{padding-bottom:10px;}
.box-basic .label, .box-basic .input{width:100%;}
.btn.big{margin-top:10px; width:100%; text-align:center;}
#content{padding-top:40px;}

@media only screen and (max-width:768px){
    #content{padding-top:0;}
}

</style>
{% endblock %}

{% block body %}
<main id="content">

    <div class="wrapper cl">
        <div class="box-basic small content">
            <h1 class="h1 title res">{{ language.translate("Recovery Password") }}</h1>
            <p>{{ language.translate("Insert your new password") }}</p>
            {% if errors %}
                <div class="box error">
                    <svg class="ico v-warning"><use xlink:href="/w/svg/icon.svg#v-warning"></use></svg>
                    <span class="txt"></span>
                </div>
            {% endif %}
            <label for="new-password" class="label">{{ language.translate("New password") }} <span class="asterisk">*</span></label>
            <input class="input" id="new-password" placeholder="{{ language.translate('New password') }}">

            <label for="confirm-password" class="label">{{ language.translate("Confirm password") }} <span class="asterisk">*</span></label>
            <input class="input" id="confirm-password" placeholder="{{ language.translate('Confirm password') }}">

            <label for="submitpass" class="btn big blue submit">
                {{ language.translate('Update password') }} <input type="submit" class="hidden2" id="submitpass">
            </label>
        </div>
    </div>
</main>


{% endblock %}

