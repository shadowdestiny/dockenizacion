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
                    <h1 class="h1 purple">Reports Sales Draw</h1>
                    {% if (salesDraw is not empty) %}
                        <table id="tableExport" class="table">
                            <thead>
                            <tr class="special">
                                <th>Lottery</th>
                                <th>Draw ID</th>
                                <th>Draw date</th>
                                <th>Draw Status</th>
                                <th>Total Bets</th>
                                <th>Gross Sales</th>
                                <th>Gross Margin</th>
                            </tr>
                            </thead>
                            <tbody>
                            {% for salesData in salesDraw %}
                                <tr>
                                    <td>{{ salesData['em'] }}</td>
                                    <td><a href="/admin/reports/salesDrawDetails?id={{ salesData['id'] }}">{{ salesData['id'] }}</a></td>
                                    <td>{{ salesData['draw_date'] }}</td>
                                    <td>{{ salesData['draw_status'] }}</td>
                                    <td>{{ salesData['totalBets'] }}</td>
                                    <td>{{ (salesData['grossSales'] / 100) | number_format (2,',','') }}</td>
                                    <td>{{ (salesData['grossMargin'] / 100) | number_format (2,',','') }}</td>
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