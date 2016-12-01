{% extends "main.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/a/css/pagination.css">
{% endblock %}

{% block bodyClass %}jackpot{% endblock %}

{% block template_scripts %}

{% endblock %}

{% block meta %}<title>Reports Customer data - Euromillions Admin System</title>{% endblock %}

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
                    <h1 class="h1 purple">Reports Customers</h1>
                    {% if (customerData is not empty) %}
                        <table class="table">
                            <thead>
                            <tr class="special">
                                <th>Name</th>
                                <th>Surname</th>
                                <th>Email</th>
                                <th>Created</th>
                                <th>Id</th>
                                <th>Currency</th>
                                <th>Country</th>
                                <th>Money deposited</th>
                                <th>Winnings</th>
                                <th>Balance</th>
                                <th>Num bets</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for data in customerData %}
                                <tr>
                                    <td>
                                        {{ data['name'] }}
                                    </td>
                                    <td>
                                        {{ data['surname'] }}
                                    </td>
                                    <td>
                                        {{ data['email'] }}
                                    </td>
                                    <td>
                                        {{ data['created'] }}
                                    </td>
                                    <td>
                                        {{ data['id'] }}
                                    </td>
                                    <td>
                                        {{ data['currency'] }}
                                    </td>
                                    <td>
                                        {{ data['country'] }}
                                    </td>
                                    <td>
                                        {{ data['money_deposited'] | number_format (2,',','') }} €
                                    </td>
                                    <td>
                                        {{ data['winnings'] | number_format (2,',','') }} €
                                    </td>
                                    <td>
                                        {{ data['balance'] | number_format (2,',','') }} €
                                    </td>
                                    <td>
                                        {{ data['num_bets'] }}
                                    </td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                    {% else %}
                        Not reports yet.
                    {% endif %}
                </div>
            </div>
        </div>
    </div>
{% endblock %}