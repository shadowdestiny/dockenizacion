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
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Monthly Sales Report</h1>
                    {% if (monthlySales is not empty) %}
                        <table class="table">
                            <thead>
                            <tr class="special">
                                <th>Month</th>
                                <th>Total Bets</th>
                                <th>Gross Sales</th>
                                <th>Gross Margin</th>
                                <th>Winnings Paid-Out</th>
                            </tr>
                            </thead>
                            <tbody>
                            {%  for sales in monthlySales %}
                            <tr>
                                <td>
                                    {{ sales['month'] }}
                                </td>
                                <td>
                                    {{ sales['total_bets'] }}
                                </td>
                                <td>
                                    {{ sales['gross_sales'] }}
                                </td>
                                <td>
                                    {{ sales['gross_margin'] }}
                                </td>
                                <td>
                                    {{ sales['winnings'] }}
                                </td>
                            </tr>
                                {%  endfor %}
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