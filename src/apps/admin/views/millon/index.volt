{% extends "main.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/a/css/pagination.css">
{% endblock %}

{% block bodyClass %}jackpot{% endblock %}

{% block template_scripts %}

{% endblock %}

{% block meta %}<title>El Millón - Euromillions Admin System</title>{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<div class="wrapper">
    <div class="container">
            <form class="cl search" method="post" action="/admin/millon/search">
                <div class="left">
                    <label for="millon">El millon:</label>
                    <input id="millon" name="millon" class="input" type="text">
                    <input id="date" placeholder="yyyy-mm-dd" name="date" class="input" type="text">
                    <button type="submit" class="">Search</button>
                </div>
            </form>
    </div>

    {% if emailUsers is not empty %}
        <div>
            <ul>
                {% for email in emailUsers %}
                <li>
                    {{ email }}
                </li>
                {% endfor %}
            </ul>
        </div>
    {% endif %}
</div>
{% endblock %}