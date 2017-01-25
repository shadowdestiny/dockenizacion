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
            $("#registrationDate_date_from").datepicker();
            $("#registrationDate_date_to").datepicker();
            $("#firstDepositDate_date_from").datepicker();
            $("#firstDepositDate_date_to").datepicker();
            $("#lastDepositDate_date_from").datepicker();
            $("#lastDepositDate_date_to").datepicker();
            $("#lastWithdrawal_date_from").datepicker();
            $("#lastWithdrawal_date_to").datepicker();
            $("#lastLoginDate_date_from").datepicker();
            $("#lastLoginDate_date_to").datepicker();
        });
    </script>
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Automation program - Create Steps</h1>
                    <p><b class="purple">Program:</b> {{ trackingCode.getName() }}</p>
                    <form method="post" action="/admin/tracking/savePreferences">
                        <input type="hidden" name="trackingCodeId" value="{{ trackingCode.getId() }}" />
                        <table class="table-program" width="100%">
                            <thead>
                            <tr class="special">
                                <th width="65%">Selection</th>
                                <th width="35%">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <table width="100%">
                                            <tr>
                                                <td width="30%">
                                                    User/ID
                                                </td>
                                                <td width="20%"><input type="checkbox" name="check_userId" value="Y" /></td>
                                                <td width="30%">
                                                    e-mail
                                                </td>
                                                <td width="20%"><input type="checkbox" name="check_email" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Country
                                                </td>
                                                <td><input type="checkbox" name="check_country" value="Y" /></td>
                                                <td>
                                                    City
                                                </td>
                                                <td><input type="checkbox" name="check_city" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Accepting emails
                                                </td>
                                                <td><input type="checkbox" name="check_acceptingEmails" value="Y" /></td>
                                                <td>
                                                    Mobile registered
                                                </td>
                                                <td><input type="checkbox" name="check_mobileRegistered" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Registration date
                                                </td>
                                                <td><input type="checkbox" name="check_registrationDate" value="Y" /></td>
                                                <!-- td>
                                                    Language
                                                </td>
                                                <td><input type="checkbox" name="check_language" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Gender
                                                </td>
                                                <td><input type="checkbox" name="gender" value="Y" /></td -->
                                                <td>
                                                    Currency
                                                </td>
                                                <td><input type="checkbox" name="check_currency" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Deposit count
                                                </td>
                                                <td><input type="checkbox" name="check_depositCount" value="Y" /></td>
                                                <td>
                                                    First Deposit date
                                                </td>
                                                <td><input type="checkbox" name="check_firstDepositDate" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Last Deposit date
                                                </td>
                                                <td><input type="checkbox" name="check_lastDepositDate" value="Y" /></td>
                                                <td>
                                                    Total Deposited
                                                </td>
                                                <td><input type="checkbox" name="check_totalDeposited" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Last Withdrawal
                                                </td>
                                                <td><input type="checkbox" name="check_lastWithdrawal" value="Y" /></td>
                                                <td>
                                                    Total Withdrawal
                                                </td>
                                                <td><input type="checkbox" name="check_totalWithdrawal" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Last login date
                                                </td>
                                                <td><input type="checkbox" name="check_lastLoginDate" value="Y" /></td>
                                                <td>
                                                    Balance
                                                </td>
                                                <td><input type="checkbox" name="check_balance" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    In/Not in Tracking Code
                                                </td>
                                                <td><input type="checkbox" name="check_inNotTrackingCode" value="Y" /></td>
                                                <td>
                                                    Lotteries played
                                                </td>
                                                <td><input type="checkbox" name="check_lotteriesPlayed" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Wagering
                                                </td>
                                                <td><input type="checkbox" name="check_wagering" value="Y" /></td>
                                                <td>
                                                    Gross Revenue
                                                </td>
                                                <td><input type="checkbox" name="check_grossRevenue" value="Y" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="border-left: 1px solid #AE5279;">
                                        <table>
                                            <tr>
                                                <td>
                                                    Credit / Debit real money
                                                </td>
                                                <td><input type="checkbox" name="check_creditDebitRealMoney" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Credit ticket
                                                </td>
                                                <td><input type="checkbox" name="check_creditTicket" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Send email
                                                </td>
                                                <td><input type="checkbox" name="check_sendEmail" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Add player to Tracking Code
                                                </td>
                                                <td><input type="checkbox" name="check_addPlayerToTrackingCode" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Remove player from Tracking Code
                                                </td>
                                                <td><input type="checkbox" name="check_removePlayerFromTrackingCode" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Move player to Tracking Code
                                                </td>
                                                <td><input type="checkbox" name="check_movePlayerToTrackingCode" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Issue bonus
                                                </td>
                                                <td><input type="checkbox" name="check_issueBonus" value="Y" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Send SMS
                                                </td>
                                                <td><input type="checkbox" name="check_sendSms" value="Y" /></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    Send Push Notification
                                                </td>
                                                <td><input type="checkbox" name="check_sendPushNotification" value="Y" /></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br />
                        <table class="table" width="100%">
                            <thead>
                            <tr class="special">
                                <th width="15%">Selection</th>
                                <th width="85%">Criteria</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr id="show_userId">
                                <td>
                                    User/ID
                                </td>
                                <td>
                                    List of users: <textarea name="userId" rows="2" style="width: 500px;"></textarea>
                                </td>
                            </tr>
                            <tr id="show_email">
                                <td>
                                    e-mail
                                </td>
                                <td>
                                    List of emails: <textarea name="email" rows="2" style="width: 500px;"></textarea>
                                </td>
                            </tr>
                            <tr id="show_country">
                                <td>
                                    Country
                                </td>
                                <td>
                                    <select multiple name="country" size="15" style="width: 500px;">
                                        {% for key, country in countryList %}
                                            <option value="{{ key }}">{{ country }}</option>
                                        {% endfor %}
                                    </select>
                                </td>
                            </tr>
                            <tr id="show_city">
                                <td>
                                    City
                                </td>
                                <td>
                                    List of cities: <textarea name="city" cols="60" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr id="show_acceptingEmails">
                                <td>
                                    Accepting emails
                                </td>
                                <td>
                                    <input type="radio" name="acceptingEmails" value="Y" /> Yes &nbsp;
                                    <input type="radio" name="acceptingEmails" value="N" /> No
                                </td>
                            </tr>
                            <tr id="show_mobileRegistered">
                                <td>
                                    Mobile registered
                                </td>
                                <td>
                                    <input type="radio" name="mobileRegistered" value="Y" /> Yes &nbsp;
                                    <input type="radio" name="mobileRegistered" value="N" /> No
                                </td>
                            </tr>
                            <tr id="show_registrationDate">
                                <td>
                                    Registration date
                                </td>
                                <td>
                                    <div>
                                        <input type="radio" name="type_registrationDate" value="dates" />
                                        From <input type="text" name="registrationDate_date_from" id="registrationDate_date_from" />
                                        To <input type="text" name="registrationDate_date_to" id="registrationDate_date_to" />
                                    </div>
                                    <div>
                                        <input type="radio" name="type_registrationDate" value="days" />
                                        From (days ago)
                                        <select name="registrationDate_days_from">
                                            <option value="0">Today</option>
                                            <option value="-1">-1</option>
                                            <option value="-2">-2</option>
                                            <option value="-3">-3</option>
                                            <option value="-4">-4</option>
                                            <option value="-5">-5</option>
                                            <option value="-6">-6</option>
                                            <option value="-7">-7</option>
                                            <option value="-8">-8</option>
                                            <option value="-9">-9</option>
                                            <option value="-10">-10</option>
                                        </select>
                                        To (days ago)
                                        <select name="registrationDate_days_to">
                                            <option value="0">Today</option>
                                            <option value="-1">-1</option>
                                            <option value="-2">-2</option>
                                            <option value="-3">-3</option>
                                            <option value="-4">-4</option>
                                            <option value="-5">-5</option>
                                            <option value="-6">-6</option>
                                            <option value="-7">-7</option>
                                            <option value="-8">-8</option>
                                            <option value="-9">-9</option>
                                            <option value="-10">-10</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <!-- tr>
                                <td>
                                    Language
                                </td>
                                <td>
                                    Languages list
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Gender
                                </td>
                                <td>
                                    Gender Options
                                </td>
                            </tr -->
                            <tr id="show_currency">
                                <td>
                                    Currency
                                </td>
                                <td>
                                    <select multiple name="currency" size="15" style="width: 500px;">
                                        {% for currency in currencyList %}
                                            <option value="{{ currency.getCode() }}">{{ currency.getCode() }} - {{ currency.getName() }}</option>
                                        {% endfor %}
                                    </select>
                                </td>
                            </tr>
                            <tr id="show_depositCount">
                                <td>
                                    Deposit count
                                </td>
                                <td>
                                    From <input type="text" name="depositCount_from" /> to <input type="text" name="depositCount_to" />
                                </td>
                            </tr>
                            <tr id="show_firstDepositDate">
                                <td>
                                    First Deposit date
                                </td>
                                <td>
                                    <div>
                                        <input type="radio" name="type_firstDepositDate" value="dates" />
                                        From <input type="text" name="firstDepositDate_date_from" id="firstDepositDate_date_from" />
                                        To <input type="text" name="firstDepositDate_date_to" id="firstDepositDate_date_to" />
                                    </div>
                                    <div>
                                        <input type="radio" name="type_firstDepositDate" value="days" />
                                        From (days ago)
                                        <select name="firstDepositDate_days_from">
                                            <option value="0">Today</option>
                                            <option value="-1">-1</option>
                                            <option value="-2">-2</option>
                                            <option value="-3">-3</option>
                                            <option value="-4">-4</option>
                                            <option value="-5">-5</option>
                                            <option value="-6">-6</option>
                                            <option value="-7">-7</option>
                                            <option value="-8">-8</option>
                                            <option value="-9">-9</option>
                                            <option value="-10">-10</option>
                                        </select>
                                        To (days ago)
                                        <select name="firstDepositDate_days_to">
                                            <option value="0">Today</option>
                                            <option value="-1">-1</option>
                                            <option value="-2">-2</option>
                                            <option value="-3">-3</option>
                                            <option value="-4">-4</option>
                                            <option value="-5">-5</option>
                                            <option value="-6">-6</option>
                                            <option value="-7">-7</option>
                                            <option value="-8">-8</option>
                                            <option value="-9">-9</option>
                                            <option value="-10">-10</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr id="show_lastDepositDate">
                                <td>
                                    Last Deposit date
                                </td>
                                <td>
                                    <div>
                                        <input type="radio" name="type_lastDepositDate" value="dates" />
                                        From <input type="text" name="lastDepositDate_date_from" id="lastDepositDate_date_from" />
                                        To <input type="text" name="lastDepositDate_date_to" id="lastDepositDate_date_to" />
                                    </div>
                                    <div>
                                        <input type="radio" name="type_lastDepositDate" value="days" />
                                        From (days ago)
                                        <select name="lastDepositDate_days_from">
                                            <option value="0">Today</option>
                                            <option value="-1">-1</option>
                                            <option value="-2">-2</option>
                                            <option value="-3">-3</option>
                                            <option value="-4">-4</option>
                                            <option value="-5">-5</option>
                                            <option value="-6">-6</option>
                                            <option value="-7">-7</option>
                                            <option value="-8">-8</option>
                                            <option value="-9">-9</option>
                                            <option value="-10">-10</option>
                                        </select>
                                        To (days ago)
                                        <select name="lastDepositDate_days_to">
                                            <option value="0">Today</option>
                                            <option value="-1">-1</option>
                                            <option value="-2">-2</option>
                                            <option value="-3">-3</option>
                                            <option value="-4">-4</option>
                                            <option value="-5">-5</option>
                                            <option value="-6">-6</option>
                                            <option value="-7">-7</option>
                                            <option value="-8">-8</option>
                                            <option value="-9">-9</option>
                                            <option value="-10">-10</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr id="show_totalDeposited">
                                <td>
                                    Total Deposited
                                </td>
                                <td>
                                    From <input type="text" name="totalDeposited_from" /> to <input type="text" name="totalDeposited_to" />
                                </td>
                            </tr>
                            <tr id="show_lastWithdrawal">
                                <td>
                                    Last Withdrawal
                                </td>
                                <td>
                                    <div>
                                        <input type="radio" name="type_lastWithdrawal" value="dates" />
                                        From <input type="text" name="lastWithdrawal_date_from" id="lastWithdrawal_date_from" />
                                        To <input type="text" name="lastWithdrawal_date_to" id="lastWithdrawal_date_to" />
                                    </div>
                                    <div>
                                        <input type="radio" name="type_lastWithdrawal" value="days" />
                                        From (days ago)
                                        <select name="lastWithdrawal_days_from">
                                            <option value="0">Today</option>
                                            <option value="-1">-1</option>
                                            <option value="-2">-2</option>
                                            <option value="-3">-3</option>
                                            <option value="-4">-4</option>
                                            <option value="-5">-5</option>
                                            <option value="-6">-6</option>
                                            <option value="-7">-7</option>
                                            <option value="-8">-8</option>
                                            <option value="-9">-9</option>
                                            <option value="-10">-10</option>
                                        </select>
                                        To (days ago)
                                        <select name="lastWithdrawal_days_to">
                                            <option value="0">Today</option>
                                            <option value="-1">-1</option>
                                            <option value="-2">-2</option>
                                            <option value="-3">-3</option>
                                            <option value="-4">-4</option>
                                            <option value="-5">-5</option>
                                            <option value="-6">-6</option>
                                            <option value="-7">-7</option>
                                            <option value="-8">-8</option>
                                            <option value="-9">-9</option>
                                            <option value="-10">-10</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr id="show_totalWithdrawal">
                                <td>
                                    Total Withdrawal
                                </td>
                                <td>
                                    From <input type="text" name="totalWithdrawal_from" /> to <input type="text" name="totalWithdrawal_to" />
                                </td>
                            </tr>
                            <tr id="show_lastLoginDate">
                                <td>
                                    Last login date
                                </td>
                                <td>
                                    <div>
                                        <input type="radio" name="type_lastLoginDate" value="dates" />
                                        From <input type="text" name="lastLoginDate_date_from" id="lastLoginDate_date_from" />
                                        To <input type="text" name="lastLoginDate_date_to" id="lastLoginDate_date_to" />
                                    </div>
                                    <div>
                                        <input type="radio" name="type_lastLoginDate" value="days" />
                                        From (days ago)
                                        <select name="lastLoginDate_days_from">
                                            <option value="0">Today</option>
                                            <option value="-1">-1</option>
                                            <option value="-2">-2</option>
                                            <option value="-3">-3</option>
                                            <option value="-4">-4</option>
                                            <option value="-5">-5</option>
                                            <option value="-6">-6</option>
                                            <option value="-7">-7</option>
                                            <option value="-8">-8</option>
                                            <option value="-9">-9</option>
                                            <option value="-10">-10</option>
                                        </select>
                                        To (days ago)
                                        <select name="lastLoginDate_days_to">
                                            <option value="0">Today</option>
                                            <option value="-1">-1</option>
                                            <option value="-2">-2</option>
                                            <option value="-3">-3</option>
                                            <option value="-4">-4</option>
                                            <option value="-5">-5</option>
                                            <option value="-6">-6</option>
                                            <option value="-7">-7</option>
                                            <option value="-8">-8</option>
                                            <option value="-9">-9</option>
                                            <option value="-10">-10</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr id="show_balance">
                                <td>
                                    Balance
                                </td>
                                <td>
                                    From <input type="text" name="balance_from" /> to <input type="text" name="balance_to" />
                                </td>
                            </tr>
                            <tr id="show_inNotTrackingCode">
                                <td>
                                    In/Not in Tracking Code
                                </td>
                                <td>
                                    <input type="radio" name="inNotTrackingCode_type" value="In" /> In &nbsp;
                                    <input type="radio" name="inNotTrackingCode_type" value="NotIn" /> Not in
                                    <br />
                                    <select multiple name="inNotTrackingCode" size="15" style="width: 300px;">
                                        {% for trackingCode in allTrackingCodes %}
                                            <option value="{{ trackingCode['id'] }}">{{ trackingCode['name'] }}</option>
                                        {% endfor %}
                                    </select>
                                </td>
                            </tr>
                            <tr id="show_lotteriesPlayed">
                                <td>
                                    Lotteries played
                                </td>
                                <td>
                                    <select multiple name="lotteriesPlayed" size="5">
                                        {% for lottery in lotteries %}
                                            <option value="{{ lottery.getId() }}">{{ lottery.getName() }}</option>
                                        {% endfor %}
                                    </select>
                                </td>
                            </tr>
                            <tr id="show_wagering">
                                <td>
                                    Wagering
                                </td>
                                <td>
                                    From <input type="text" name="wagering_from" /> to <input type="text" name="wagering_to" />
                                </td>
                            </tr>
                            <tr id="show_grossRevenue">
                                <td>
                                    Gross Revenue
                                </td>
                                <td>
                                    From <input type="text" name="grossRevenue_from" /> to <input type="text" name="grossRevenue_to" />
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