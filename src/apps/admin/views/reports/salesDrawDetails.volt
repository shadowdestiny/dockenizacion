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
                    <h1 class="h1 purple">Reports Sales Draw - Draw {{ euromillionsDrawId }} Details</h1>
                    {% if (salesDrawDetailsData is not empty) %}
                        <table id="tableExport" class="table">
                            <thead>
                            <tr class="special">
                                <th>User</th>
                                <th>Country</th>
                                <th>Transaction ID</th>
                                {#<th>Bet ID</th>#}
                                <th>Purchase date</th>
                                <th>Bet Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for detailsData in salesDrawDetailsData %}
                                <tr>
                                    <td align="center">
                                        {{ detailsData['user'] }}
                                    </td>
                                    <td align="center">
                                        {{ countryList[detailsData['country']] }}
                                    </td>
                                    <td align="center">
                                        {{ detailsData['transactionID'] }}
                                    </td>
                                    {#<td>#}
                                        {#{{ detailsData['betId'] }}#}
                                    {#</td>#}
                                    <td align="center">
                                        {{ detailsData['purchaseDate'] }}
                                    </td>
                                    <td align="center">
                                        {{ "%.2f"|format(detailsData['movement'] ) }} &euro;
                                    </td>
                                </tr>
                            {% endfor %}
                            </tbody>
                        </table>
                        <p align="center"><input type="button" value="Download" id="export" class="btn btn-primary" /></p>
                    {% else %}
                        Not reports yet.
                    {% endif %}
                </div>
            </div>
        </div>
    </div>
{% endblock %}