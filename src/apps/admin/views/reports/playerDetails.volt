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
        function downloadBets(id) {
            location='/admin/reports/downloadBets?id='+id;
        }

        function downloadDeposits(id) {
            location='/admin/reports/downloadDeposits?id='+id;
        }
    </script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">{{ user.getName() }} {{ user.getSurname() }}:</h1>
                    <table width="100%" style="border: 1px dotted red;" cellspacing="10">
                        <tr>
                            <td width="30%"><b>Email</b>: <a href="mailto:{{ user.getEmail() }}">{{ user.getEmail() }}</a></td>
                            <td><b>Phone</b>: {{ user.getPhoneNumber() }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Address</b>: {{ user.getStreet() }}</td>
                        </tr>
                        <tr>
                            <td><b>Balance</b>: {{ (user.getWallet().getUploaded().getAmount() + user.getWallet().getWinnings().getAmount()) / 100 }} €</td>
                            <td><b>Withdrawable</b>: {{ user.getWallet().getWinnings().getAmount() / 100 }} €</td>
                        </tr>
                    </table>

                    {% include "reports/playerDetails/_upcomingDraws.volt" %}

                    {% include "reports/playerDetails/_subscriptionActives.volt" %}

                    {% include "reports/playerDetails/_pastGames.volt" %}

                    {% include "reports/playerDetails/_bets.volt" %}

                    {% include "reports/playerDetails/_deposits.volt" %}

                    {% include "reports/playerDetails/_withdrawals.volt" %}

                    {#<p align="center"><input type="button" onclick="window.history.back();" value="Go Back" class="btn btn-primary" /></p>#}
                </div>
            </div>
        </div>
    </div>
{% endblock %}