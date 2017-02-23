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
    <script language="javascript">
        $(function () {
            $("#export").click(function(){
                $("#tableExport").tableToCSV();
            });
        });
    </script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Player Reports</h1>
                    {% if playersList is not empty %}
                    <table class="table-program" width="100%" id="tableExport">
                        <thead>
                        <tr class="special">
                            {% if 'user' in headerList %}<th>ID</th>{% endif %}
                            {% if 'name' in headerList %}<th>Name</th>{% endif %}
                            {% if 'surname' in headerList %}<th>Surname</th>{% endif %}
                            <th>Email</th>
                            {% if 'phone' in headerList %}<th>Phone</th>{% endif %}
                            {% if 'country' in headerList %}<th>Country</th>{% endif %}
                            {% if 'city' in headerList %}<th>City</th>{% endif %}
                            {% if 'street' in headerList %}<th>Street</th>{% endif %}
                            {% if 'ip' in headerList %}<th>IP</th>{% endif %}
                            {% if 'LastLoginDate' in headerList %}<th>Last login date</th>{% endif %}
                            {% if 'registrationDate' in headerList %}<th>Registration date</th>{% endif %}
                            {% if 'winnings' in headerList %}<th>Winnings</th>{% endif %}
                            {% if 'balance' in headerList %}<th>Balance</th>{% endif %}
                            {% if 'deposited' in headerList %}<th>Deposited</th>{% endif %}
                            {% if 'numberDeposits' in headerList %}<th>Number deposits</th>{% endif %}
                            {% if 'amountDeposited' in headerList %}<th>Amount deposited</th>{% endif %}
                            {% if 'LastDepositDate' in headerList %}<th>Last deposit date</th>{% endif %}
                            {% if 'numberWithdrawals' in headerList %}<th>Number withdrawals</th>{% endif %}
                            {% if 'amountWithdraw' in headerList %}<th>Amount withdraw</th>{% endif %}
                            {% if 'ggr' in headerList %}<th>GGR</th>{% endif %}
                        </tr>
                        </thead>
                        <tbody>
                        {% for player in playersList %}
                        <tr>
                            {% if 'user' in headerList %}<td>{{ player['id'] }}</td>{% endif %}
                            {% if 'name' in headerList %}<td>{{ player['name'] }}</td>{% endif %}
                            {% if 'surname' in headerList %}<td>{{ player['surname'] }}</td>{% endif %}
                            <td><a href="/admin/reports/playerDetails?id={{ player['id'] }}">{{ player['email'] }}</a></td>
                            {% if 'phone' in headerList %}<td>{{ player['phone'] }}</td>{% endif %}
                            {% if 'country' in headerList %}<td>{{ countryList[player['country']] }}</td>{% endif %}
                            {% if 'city' in headerList %}<td>{{ player['city'] }}</td>{% endif %}
                            {% if 'street' in headerList %}<td>{{ player['street'] }}</td>{% endif %}
                            {% if 'ip' in headerList %}<td>{{ player['ip'] }}</td>{% endif %}
                            {% if 'LastLoginDate' in headerList %}<td>{{ player['LastLoginDate'] }}</td>{% endif %}
                            {% if 'registrationDate' in headerList %}<td>{{ player['registrationDate'] }}</td>{% endif %}
                            {% if 'winnings' in headerList %}<td>{{ player['winnings'] / 100 }}</td>{% endif %}
                            {% if 'balance' in headerList %}<td>{{ player['balance'] / 100 }}</td>{% endif %}
                            {% if 'deposited' in headerList %}<td>{% if player['numberDeposits'] > 0 %}Y{% else %}N{% endif %}</td>{% endif %}
                            {% if 'numberDeposits' in headerList %}<td>{{ player['numberDeposits'] }}</td>{% endif %}
                            {% if 'amountDeposited' in headerList %}<td>{{ player['amountDeposited'] / 100 }}</td>{% endif %}
                            {% if 'LastDepositDate' in headerList %}<td>{{ player['LastDepositDate'] }}</td>{% endif %}
                            {% if 'numberWithdrawals' in headerList %}<td>{{ player['numberWithdrawals'] }}</td>{% endif %}
                            {% if 'amountWithdraw' in headerList %}<td>{{ player['amountWithdraw'] / 100 }}</td>{% endif %}
                            {% if 'ggr' in headerList %}<td>{% if playersGGRList[player['id']] is defined %}{{ playersGGRList[player['id']] }}{% else %} 0{% endif %}</td>{% endif %}
                        </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    {% else %}
                        <p align="center">No players found with this search conditions</p>
                    {% endif %}
                        <p align="center">
                            <input type="button" onclick="window.history.back();" value="Go Back" class="btn btn-primary" />
                            {% if playersList is not empty %}
                                 &nbsp; <input type="button" value="Download" id="export" class="btn btn-primary" />
                            {% endif %}
                        </p>
                </div>
            </div>
        </div>
    </div>
{% endblock %}