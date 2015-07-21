{% extends "main.volt" %}
{% block bodyClass %}contact{% endblock %}

{% block header %}{% include "_elements/header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic">
            <h1 class="h2">{{ language.translate("Contact us") }}</h1>

            <p>{{ language.translate("xxx") }}</p>

            <form novalidate>

                <div class="box error">
                    Error info lorem ipsum
                </div>

                <label for="name" class="label">
                    <span class="txt">Full Name</span>
                    <input id="name" class="input" type="text">
                </label>

             
                <div class="cl">
                    <a href="javascript:void(0);" class="btn blue submit right">{{ language.translate("Request new password") }}</a>
                </div>
            </form>
        </div>
    </div>
</main>

{% endblock %}