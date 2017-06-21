{% extends "main.volt" %}

{% block bodyClass %}news{% endblock %}

{% block meta %}<title>Logout - Euromillions Admin System</title>{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Logout</h1>
                    <div class="stream-composer media">
                        Te has desconectado correctamente.<br /><br />
                        <a href="/admin/login/">Login</a>
                    </div>
                </div><!--/.module-body-->
            </div>
        </div>
    </div>
{% endblock %}