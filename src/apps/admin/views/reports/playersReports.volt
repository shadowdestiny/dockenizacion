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
        });
    </script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Player Reports</h1>
                    <form method="post" action="/admin/reports/playersReportsResults">
                    <table class="table-program" width="100%">
                        <thead>
                        <tr class="special">
                            <th>Filters</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            Name
                                        </td>
                                        <td>
                                            <input type="text" name="name" id="name" />
                                        </td>
                                        <td>
                                            User ID
                                        </td>
                                        <td>
                                            <input type="text" name="user" id="user" />
                                        </td>
                                        <td>
                                            Tracking Code
                                            <select name="trackingCode" id="trackingCode">
                                                <option value="0">[-- Select --]</option>
                                                {% for trackingCode in allTrackingCodes %}
                                                    <option value="{{ trackingCode['id'] }}">{{ trackingCode['name'] }}</option>
                                                {% endfor %}
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Surname
                                        </td>
                                        <td>
                                            <input type="text" name="surname" id="surname" />
                                        </td>
                                        <td>
                                            Countries
                                        </td>
                                        <td>
                                            <select name="countries[]" id="countries" multiple size="5" style="width: 300px;">
                                                {% for key, country in countryList %}
                                                    <option value="{{ key }}">{{ country }}</option>
                                                {% endfor %}
                                            </select>
                                        </td>
                                        <td>
                                            Date:
                                            From <input type="text" name="dateFrom" id="dateFrom" style="width: 100px;" />
                                            To <input type="text" name="dateTo" id="dateTo" style="width: 100px;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Email
                                        </td>
                                        <td>
                                            <input type="text" name="email" id="email" />
                                        </td>
                                        <td>
                                            Depositor
                                        </td>
                                        <td>
                                            <input type="radio" name="depositor" value="Y" /> Yes
                                            <input type="radio" name="depositor" value="N" /> No
                                        </td>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table-program" width="100%">
                        <thead>
                        <tr class="special">
                            <th>Data</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td>
                                            User
                                        </td>
                                        <td width="20px">
                                            <input type="checkbox" name="check_user" value="Y" />
                                        </td>
                                        <td width="20%">&nbsp;</td>
                                        <td>
                                            Gender
                                        </td>
                                        <td width="20px">
                                            <span style="color: red;">X</span>
                                        </td>
                                        <td width="20%">&nbsp;</td>
                                        <td>
                                            Balance
                                        </td>
                                        <td width="20px">
                                            <input type="checkbox" name="check_balance" value="Y" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Name
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_name" value="Y" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            Ip
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_ip" value="Y" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            Winnings
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_winnings" value="Y" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Surname
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_surname" value="Y" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            Registration Date
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_registrationDate" value="Y" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            GGR
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_ggr" value="Y" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Email
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_email" value="Y" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            Deposited
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_deposited" value="Y" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            Bonus Cost
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_bonusCost" value="Y" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Phone
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_phone" value="Y" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            Number of deposits
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_numberDeposits" value="Y" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Country
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_country" value="Y" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            Amount Deposited
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_amountDeposited" value="Y" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            City
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_city" value="Y" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            Number of withdrawals
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_numberWithdrawals" value="Y" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Address
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_street" value="Y" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            Amount withdraw
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_amountWithdraw" value="Y" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Language
                                        </td>
                                        <td>
                                            <span style="color: red;">X</span>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            Last Deposit Date
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_LastDepositDate" value="Y" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Birthday
                                        </td>
                                        <td>
                                            <span style="color:red;">X</span>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            Last Login Date
                                        </td>
                                        <td>
                                            <input type="checkbox" name="check_LastLoginDate" value="Y" />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <input type="submit" value="Show" class="btn btn-primary" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
{% endblock %}