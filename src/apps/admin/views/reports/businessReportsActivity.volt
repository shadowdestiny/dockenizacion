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
            $("#dateFrom").datepicker();
            $("#dateTo").datepicker();

            $("#showActivity").click(function(){
                if (!$('input[name=groupBy]:checked').val() || !$("#dateFrom").val() || !$("#dateTo").val()) {
                    alert('You must fill in dates and group by');
                } else {
                    $.ajax({
                        url: 'businessReportsActivityResult',
                        type: 'POST',
                        data: {
                            dateFrom: $("#dateFrom").val(),
                            dateTo: $("#dateTo").val(),
                            countries: $("#countries").val(),
                            groupBy: $('input[name=groupBy]:checked').val(),
                        },
                        success: function(data) {
                            $('#resultsActivity').html(data);
                        },
                        error: function() {
                            alert('Something went wrong, please try again.');
                        },
                    });
                }
            });
        });
    </script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Business Reports - Activity</h1>
                    <form>
                        <table>
                            <tr>
                                <td>
                                    <b>Date:</b> From <input type="text" name="dateFrom" id="dateFrom" /> To <input type="text" name="dateTo" id="dateTo" />
                                </td>
                                <td>
                                    <b>Country</b>
                                    <select name="countries[]" id="countries" multiple size="5" style="width: 300px;">
                                        {% for key, country in countryList %}
                                            <option value="{{ key }}">{{ country }}</option>
                                        {% endfor %}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <b>Group by: </b>
                                        <input type="radio" name="groupBy" value="day" /> Day &nbsp;
                                        <input type="radio" name="groupBy" value="month" /> Month &nbsp;
                                        <input type="radio" name="groupBy" value="year" /> Year
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <input type="button" value="Show" class="btn btn-primary" id="showActivity" />
                                </td>
                            </tr>
                        </table>
                    </form>
                    <div id="resultsActivity"></div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}