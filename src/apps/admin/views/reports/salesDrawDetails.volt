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
                                <th>Bet ID</th>
                                <th>Numbers played</th>
                                <th>Purchase date</th>
                                <th>Bet Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for detailsData in salesDrawDetailsData %}
                                <?php $dataExplode = explode('#', $detailsData['data']); ?>
                                {% if (dataExplode[1] > 1) %}
                                    {% for i in 1..dataExplode[1] %}
                                        <tr>
                                            <td align="center">
                                                {{ detailsData['email'] }}
                                            </td>
                                            <td align="center">
                                                {{ countryList[detailsData['country']] }}
                                            </td>
                                            <td align="center">
                                                {{ detailsData['transactionID'] }}
                                            </td>
                                            <td>
                                                {% if detailsData['betIds'] is defined %}
                                                    {{ detailsData['betIds']['id'][i-1] }}
                                                {% endif %}
                                            </td>
                                            <td>
                                                {% if detailsData['betIds'] is defined %}
                                                    {{ detailsData['betIds']['numbers'][i-1] }}
                                                {% endif %}
                                            </td>
                                            <td align="center">
                                                {{ detailsData['purchaseDate'] }}
                                            </td>
                                            <td align="center">
                                                {{ "%.2f"|format(detailsData['movement'] / dataExplode[1]) }} &euro;
                                            </td>
                                        </tr>
                                    {% endfor %}
                                {% else %}
                                    <tr>
                                        <td align="center">
                                            {{ detailsData['email'] }}
                                        </td>
                                        <td align="center">
                                            {{ countryList[detailsData['country']] }}
                                        </td>
                                        <td align="center">
                                            {{ detailsData['transactionID'] }}
                                        </td>
                                        <td>
                                            {% if detailsData['betIds'] is defined %}
                                                {{ detailsData['betIds']['id'][0] }}
                                            {% endif %}
                                        </td>
                                        <td>
                                            {% if detailsData['betIds'] is defined %}
                                                {{ detailsData['betIds']['numbers'][0] }}
                                            {% endif %}
                                        </td>
                                        <td align="center">
                                            {{ detailsData['purchaseDate'] }}
                                        </td>
                                        <td align="center">
                                            {{ "%.2f"|format(detailsData['movement'] ) }} &euro;
                                        </td>
                                    </tr>
                                {% endif %}
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