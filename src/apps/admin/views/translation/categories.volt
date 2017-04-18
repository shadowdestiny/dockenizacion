{% extends "main.volt" %}

{% block template_css %}{% endblock %}

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
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Categories</h1>
                    {% if categoriesList is not empty %}
                        <table class="table">
                            <thead>
                            <tr class="special">
                                <th>Category</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for category in categoriesList %}
                                <tr>
                                    <td>
                                        {{ dump(category) }}
                                    </td>
                                </tr>
                            {%  endfor %}
                            </tbody>
                        </table>
                    {% else %}
                        Not categories yet.
                    {% endif %}
                </div>
            </div>
        </div>
    </div>
{% endblock %}