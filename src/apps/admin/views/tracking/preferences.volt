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

        function showSelection(value)
        {
            var arrayValue = value.split('_');
            if ($('input[name='+value+']:checked').length == 0) {
                $('#show_'+arrayValue[1]).addClass('display-none');
            } else {
                $('#show_'+arrayValue[1]).removeClass('display-none');
            }
        }
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
                                                <td width="20%"><input type="checkbox" name="check_userId" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['userId'] is defined) %} checked {% endif %} /></td>
                                                <td width="30%">
                                                    e-mail
                                                </td>
                                                <td width="20%"><input type="checkbox" name="check_email" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['email'] is defined) %} checked {% endif %} /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Country
                                                </td>
                                                <td><input type="checkbox" name="check_country" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['country'] is defined) %} checked {% endif %} /></td>
                                                <td>
                                                    City
                                                </td>
                                                <td><input type="checkbox" name="check_city" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['city'] is defined) %} checked {% endif %} /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Accepting emails
                                                </td>
                                                <td><input type="checkbox" name="check_acceptingEmails" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['acceptingEmails'] is defined) %} checked {% endif %} /></td>
                                                <td>
                                                    Mobile registered
                                                </td>
                                                <td><input type="checkbox" name="check_mobileRegistered" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['mobileRegistered'] is defined) %} checked {% endif %} /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Registration date
                                                </td>
                                                <td><input type="checkbox" name="check_registrationDate" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['registrationDate'] is defined) %} checked {% endif %} /></td>
                                                <!-- td>
                                                    Language
                                                </td>
                                                <td><input type="checkbox" name="check_language" value="Y" onclick="showSelection(this.name)" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Gender
                                                </td>
                                                <td><input type="checkbox" name="gender" value="Y" onclick="showSelection(this.name)" /></td -->
                                                <td>
                                                    Currency
                                                </td>
                                                <td><input type="checkbox" name="check_currency" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['currency'] is defined) %} checked {% endif %} /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Deposit count
                                                </td>
                                                <td><input type="checkbox" name="check_depositCount" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['depositCount'] is defined) %} checked {% endif %} /></td>
                                                <td>
                                                    First Deposit date
                                                </td>
                                                <td><input type="checkbox" name="check_firstDepositDate" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['firstDepositDate'] is defined) %} checked {% endif %} /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Last Deposit date
                                                </td>
                                                <td><input type="checkbox" name="check_lastDepositDate" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['lastDepositDate'] is defined) %} checked {% endif %} /></td>
                                                <td>
                                                    Total Deposited
                                                </td>
                                                <td><input type="checkbox" name="check_totalDeposited" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['totalDeposited'] is defined) %} checked {% endif %} /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Last Withdrawal
                                                </td>
                                                <td><input type="checkbox" name="check_lastWithdrawal" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['lastWithdrawal'] is defined) %} checked {% endif %} /></td>
                                                <td>
                                                    Total Withdrawal
                                                </td>
                                                <td><input type="checkbox" name="check_totalWithdrawal" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['totalWithdrawal'] is defined) %} checked {% endif %} /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Last login date
                                                </td>
                                                <td><input type="checkbox" name="check_lastLoginDate" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['lastLoginDate'] is defined) %} checked {% endif %} /></td>
                                                <td>
                                                    Balance
                                                </td>
                                                <td><input type="checkbox" name="check_balance" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['balance'] is defined) %} checked {% endif %} /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    In/Not in Tracking Code
                                                </td>
                                                <td><input type="checkbox" name="check_inNotTrackingCode" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['inNotTrackingCode'] is defined) %} checked {% endif %} /></td>
                                                <td>
                                                    Lotteries played
                                                </td>
                                                <td><input type="checkbox" name="check_lotteriesPlayed" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['lotteriesPlayed'] is defined) %} checked {% endif %} /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Wagering
                                                </td>
                                                <td><input type="checkbox" name="check_wagering" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['wagering'] is defined) %} checked {% endif %} /></td>
                                                <td>
                                                    Gross Revenue
                                                </td>
                                                <td><input type="checkbox" name="check_grossRevenue" value="Y" onclick="showSelection(this.name)" {% if (attributesChecked['grossRevenue'] is defined) %} checked {% endif %} /></td>
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
                            <tr class="special">
                                <th width="15%">Selection</th>
                                <th width="85%">Criteria</th>
                            </tr>
                            <tr id="show_userId" {% if (attributesChecked['userId'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    User/ID
                                </td>
                                <td>
                                    List of users: <textarea name="userId" rows="2" style="width: 500px;">{% if (attributesChecked['userId'] is defined) %}{{attributes[attributesChecked['userId_key']].getConditions()}}{% endif %}</textarea>
                                </td>
                            </tr>
                            <tr id="show_email" {% if (attributesChecked['email'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    e-mail
                                </td>
                                <td>
                                    List of emails: <textarea name="email" rows="2" style="width: 500px;">{% if (attributesChecked['email'] is defined) %}{{ attributes[attributesChecked['email_key']].getConditions() }}{% endif %}</textarea>
                                </td>
                            </tr>
                            <tr id="show_country" {% if (attributesChecked['country'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Country
                                    {% if (attributesChecked['country'] is defined) %}
                                        <?php $countryIds = explode(',', $attributes[$attributesChecked['country_key']]->getConditions()); ?>
                                    {% else %}
                                        <?php $countryIds = ''; ?>
                                    {% endif %}
                                </td>
                                <td>
                                    <select multiple name="country[]" size="15" style="width: 500px;">
                                        {% for key, country in countryList %}
                                            <option value="{{ key }}" {% if key in countryIds %} selected {% endif %}>{{ country }}</option>
                                        {% endfor %}
                                    </select>
                                </td>
                            </tr>
                            <tr id="show_city" {% if (attributesChecked['city'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    City
                                </td>
                                <td>
                                    List of cities: <textarea name="city" cols="60" rows="2">{% if (attributesChecked['city'] is defined) %}{{ attributes[attributesChecked['city_key']].getConditions() }}{% endif %}</textarea>
                                </td>
                            </tr>
                            <tr id="show_acceptingEmails" {% if (attributesChecked['acceptingEmails'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Accepting emails
                                </td>
                                <td>
                                    {% if (attributesChecked['acceptingEmails'] is defined) %}
                                        {% set radioAcceptionEmails = attributes[attributesChecked['acceptingEmails_key']].getConditions() %}
                                    {% else %}
                                        {% set radioAcceptionEmails = '' %}
                                    {% endif %}
                                    <input type="radio" name="acceptingEmails" value="Y" {% if radioAcceptionEmails == 'Y' %} checked {% endif %} /> Yes &nbsp;
                                    <input type="radio" name="acceptingEmails" value="N" {% if radioAcceptionEmails == 'N' %} checked {% endif %} /> No
                                </td>
                            </tr>
                            <tr id="show_mobileRegistered" {% if (attributesChecked['mobileRegistered'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Mobile registered
                                </td>
                                <td>
                                    {% if (attributesChecked['mobileRegistered'] is defined) %}
                                        {% set radioMobileRegistered = attributes[attributesChecked['mobileRegistered_key']].getConditions() %}
                                    {% else %}
                                        {% set radioMobileRegistered = '' %}
                                    {% endif %}
                                    <input type="radio" name="mobileRegistered" value="Y" {% if radioMobileRegistered == 'Y' %} checked {% endif %} /> Yes &nbsp;
                                    <input type="radio" name="mobileRegistered" value="N" {% if radioMobileRegistered == 'N' %} checked {% endif %} /> No
                                </td>
                            </tr>
                            <tr id="show_registrationDate" {% if (attributesChecked['registrationDate'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Registration date
                                </td>
                                <td>
                                    {% if (attributesChecked['registrationDate'] is defined) %}
                                        <?php
                                            $registrationDateConditions = explode(',', $attributes[$attributesChecked['registrationDate_key']]->getConditions());
                                            if (strlen($registrationDateConditions[0]) > 3) {
                                                $radioRegistrationDate = 'dates';
                                            } else {
                                                $radioRegistrationDate = 'days';
                                            }
                                        ?>
                                    {% else %}
                                        {% set registrationDateConditions = ['',''] %}
                                        {% set radioRegistrationDate = '' %}
                                    {% endif %}
                                    <div>
                                        <input type="radio" name="type_registrationDate" value="dates" {% if radioRegistrationDate == 'dates' %} checked {% endif %} />
                                        From <input type="text" name="registrationDate_date_from" id="registrationDate_date_from" {% if radioRegistrationDate == 'dates' %} value="{{ registrationDateConditions[0] }}" {% endif %} />
                                        To <input type="text" name="registrationDate_date_to" id="registrationDate_date_to" {% if radioRegistrationDate == 'dates' %} value="{{ registrationDateConditions[1] }}" {% endif %} />
                                    </div>
                                    <div>
                                        <input type="radio" name="type_registrationDate" value="days" {% if radioRegistrationDate == 'days' %} checked {% endif %} />
                                        From (days ago)
                                        <select name="registrationDate_days_from">
                                            <option value="0" {% if "0" == registrationDateConditions[0] %} selected {% endif %}>Today</option>
                                            <option value="-1" {% if "-1" == registrationDateConditions[0] %} selected {% endif %}>-1</option>
                                            <option value="-2" {% if "-2" == registrationDateConditions[0] %} selected {% endif %}>-2</option>
                                            <option value="-3" {% if "-3" == registrationDateConditions[0] %} selected {% endif %}>-3</option>
                                            <option value="-4" {% if "-4" == registrationDateConditions[0] %} selected {% endif %}>-4</option>
                                            <option value="-5" {% if "-5" == registrationDateConditions[0] %} selected {% endif %}>-5</option>
                                            <option value="-6" {% if "-6" == registrationDateConditions[0] %} selected {% endif %}>-6</option>
                                            <option value="-7" {% if "-7" == registrationDateConditions[0] %} selected {% endif %}>-7</option>
                                            <option value="-8" {% if "-8" == registrationDateConditions[0] %} selected {% endif %}>-8</option>
                                            <option value="-9" {% if "-9" == registrationDateConditions[0] %} selected {% endif %}>-9</option>
                                            <option value="-10" {% if "-10" == registrationDateConditions[0] %} selected {% endif %}>-10</option>
                                        </select>
                                        To (days ago)
                                        <select name="registrationDate_days_to">
                                            <option value="0" {% if "0" == registrationDateConditions[1] %} selected {% endif %}>Today</option>
                                            <option value="-1" {% if "-1" == registrationDateConditions[1] %} selected {% endif %}>-1</option>
                                            <option value="-2" {% if "-2" == registrationDateConditions[1] %} selected {% endif %}>-2</option>
                                            <option value="-3" {% if "-3" == registrationDateConditions[1] %} selected {% endif %}>-3</option>
                                            <option value="-4" {% if "-4" == registrationDateConditions[1] %} selected {% endif %}>-4</option>
                                            <option value="-5" {% if "-5" == registrationDateConditions[1] %} selected {% endif %}>-5</option>
                                            <option value="-6" {% if "-6" == registrationDateConditions[1] %} selected {% endif %}>-6</option>
                                            <option value="-7" {% if "-7" == registrationDateConditions[1] %} selected {% endif %}>-7</option>
                                            <option value="-8" {% if "-8" == registrationDateConditions[1] %} selected {% endif %}>-8</option>
                                            <option value="-9" {% if "-9" == registrationDateConditions[1] %} selected {% endif %}>-9</option>
                                            <option value="-10" {% if "-10" == registrationDateConditions[1] %} selected {% endif %}>-10</option>
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
                            <tr id="show_currency" {% if (attributesChecked['currency'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Currency
                                    {% if (attributesChecked['currency'] is defined) %}
                                        <?php $currencyIds = explode(',', $attributes[$attributesChecked['currency_key']]->getConditions()); ?>
                                    {% else %}
                                        <?php $currencyIds = ''; ?>
                                    {% endif %}
                                </td>
                                <td>
                                    <select multiple name="currency[]" size="15" style="width: 500px;">
                                        {% for currency in currencyList %}
                                            <option value="{{ currency.getCode() }}" {% if currency.getCode() in currencyIds %} selected {% endif %}>{{ currency.getCode() }} - {{ currency.getName() }}</option>
                                        {% endfor %}
                                    </select>
                                </td>
                            </tr>
                            <tr id="show_depositCount" {% if (attributesChecked['depositCount'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Deposit count
                                    {% if (attributesChecked['depositCount'] is defined) %}
                                        <?php $depositCountConditions = explode(',', $attributes[$attributesChecked['depositCount_key']]->getConditions()); ?>
                                    {% else %}
                                        <?php $depositCountConditions = ['','']; ?>
                                    {% endif %}
                                </td>
                                <td>
                                    From <input type="text" name="depositCount_from" value="{{ depositCountConditions[0] }}" /> to <input type="text" name="depositCount_to" value="{{ depositCountConditions[1] }}" />
                                </td>
                            </tr>
                            <tr id="show_firstDepositDate" {% if (attributesChecked['firstDepositDate'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    First Deposit date
                                </td>
                                <td>
                                    {% if (attributesChecked['firstDepositDate'] is defined) %}
                                        <?php
                                            $firstDepositDateConditions = explode(',', $attributes[$attributesChecked['firstDepositDate_key']]->getConditions());
                                            if (strlen($firstDepositDateConditions[0]) > 3) {
                                                $radioFirstDepositDate = 'dates';
                                            } else {
                                                $radioFirstDepositDate = 'days';
                                            }
                                        ?>
                                    {% else %}
                                        {% set firstDepositDateConditions = ['',''] %}
                                        {% set radioFirstDepositDate = '' %}
                                    {% endif %}
                                    <div>
                                        <input type="radio" name="type_firstDepositDate" value="dates" {% if radioFirstDepositDate == 'dates' %} checked {% endif %} />
                                        From <input type="text" name="firstDepositDate_date_from" id="firstDepositDate_date_from" {% if radioFirstDepositDate == 'dates' %} value="{{ firstDepositDateConditions[0] }}" {% endif %} />
                                        To <input type="text" name="firstDepositDate_date_to" id="firstDepositDate_date_to" {% if radioFirstDepositDate == 'dates' %} value="{{ firstDepositDateConditions[1] }}" {% endif %} />
                                    </div>
                                    <div>
                                        <input type="radio" name="type_firstDepositDate" value="days" {% if radioFirstDepositDate == 'days' %} checked {% endif %} />
                                        From (days ago)
                                        <select name="firstDepositDate_days_from">
                                            <option value="0" {% if "0" == firstDepositDateConditions[0] %} selected {% endif %}>Today</option>
                                            <option value="-1" {% if "-1" == firstDepositDateConditions[0] %} selected {% endif %}>-1</option>
                                            <option value="-2" {% if "-2" == firstDepositDateConditions[0] %} selected {% endif %}>-2</option>
                                            <option value="-3" {% if "-3" == firstDepositDateConditions[0] %} selected {% endif %}>-3</option>
                                            <option value="-4" {% if "-4" == firstDepositDateConditions[0] %} selected {% endif %}>-4</option>
                                            <option value="-5" {% if "-5" == firstDepositDateConditions[0] %} selected {% endif %}>-5</option>
                                            <option value="-6" {% if "-6" == firstDepositDateConditions[0] %} selected {% endif %}>-6</option>
                                            <option value="-7" {% if "-7" == firstDepositDateConditions[0] %} selected {% endif %}>-7</option>
                                            <option value="-8" {% if "-8" == firstDepositDateConditions[0] %} selected {% endif %}>-8</option>
                                            <option value="-9" {% if "-9" == firstDepositDateConditions[0] %} selected {% endif %}>-9</option>
                                            <option value="-10" {% if "-10" == firstDepositDateConditions[0] %} selected {% endif %}>-10</option>
                                        </select>
                                        To (days ago)
                                        <select name="firstDepositDate_days_to">
                                            <option value="0" {% if "0" == firstDepositDateConditions[1] %} selected {% endif %}>Today</option>
                                            <option value="-1" {% if "-1" == firstDepositDateConditions[1] %} selected {% endif %}>-1</option>
                                            <option value="-2" {% if "-2" == firstDepositDateConditions[1] %} selected {% endif %}>-2</option>
                                            <option value="-3" {% if "-3" == firstDepositDateConditions[1] %} selected {% endif %}>-3</option>
                                            <option value="-4" {% if "-4" == firstDepositDateConditions[1] %} selected {% endif %}>-4</option>
                                            <option value="-5" {% if "-5" == firstDepositDateConditions[1] %} selected {% endif %}>-5</option>
                                            <option value="-6" {% if "-6" == firstDepositDateConditions[1] %} selected {% endif %}>-6</option>
                                            <option value="-7" {% if "-7" == firstDepositDateConditions[1] %} selected {% endif %}>-7</option>
                                            <option value="-8" {% if "-8" == firstDepositDateConditions[1] %} selected {% endif %}>-8</option>
                                            <option value="-9" {% if "-9" == firstDepositDateConditions[1] %} selected {% endif %}>-9</option>
                                            <option value="-10" {% if "-10" == firstDepositDateConditions[1] %} selected {% endif %}>-10</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr id="show_lastDepositDate" {% if (attributesChecked['lastDepositDate'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Last Deposit date
                                </td>
                                <td>
                                    {% if (attributesChecked['lastDepositDate'] is defined) %}
                                        <?php
                                            $lastDepositDateConditions = explode(',', $attributes[$attributesChecked['lastDepositDate_key']]->getConditions());
                                            if (strlen($lastDepositDateConditions[0]) > 3) {
                                                $radioLastDepositDate = 'dates';
                                            } else {
                                                $radioLastDepositDate = 'days';
                                            }
                                        ?>
                                    {% else %}
                                        {% set lastDepositDateConditions = ['',''] %}
                                        {% set radioLastDepositDate = '' %}
                                    {% endif %}
                                    <div>
                                        <input type="radio" name="type_lastDepositDate" value="dates" {% if radioLastDepositDate == 'dates' %} checked {% endif %} />
                                        From <input type="text" name="lastDepositDate_date_from" id="lastDepositDate_date_from" {% if radioLastDepositDate == 'dates' %} value="{{ lastDepositDateConditions[0] }}" {% endif %} />
                                        To <input type="text" name="lastDepositDate_date_to" id="lastDepositDate_date_to"  {% if radioLastDepositDate == 'dates' %} value="{{ lastDepositDateConditions[1] }}" {% endif %} />
                                    </div>
                                    <div>
                                        <input type="radio" name="type_lastDepositDate" value="days" {% if radioLastDepositDate == 'days' %} checked {% endif %} />
                                        From (days ago)
                                        <select name="lastDepositDate_days_from">
                                            <option value="0" {% if "0" == lastDepositDateConditions[0] %} selected {% endif %}>Today</option>
                                            <option value="-1" {% if "-1" == lastDepositDateConditions[0] %} selected {% endif %}>-1</option>
                                            <option value="-2" {% if "-2" == lastDepositDateConditions[0] %} selected {% endif %}>-2</option>
                                            <option value="-3" {% if "-3" == lastDepositDateConditions[0] %} selected {% endif %}>-3</option>
                                            <option value="-4" {% if "-4" == lastDepositDateConditions[0] %} selected {% endif %}>-4</option>
                                            <option value="-5" {% if "-5" == lastDepositDateConditions[0] %} selected {% endif %}>-5</option>
                                            <option value="-6" {% if "-6" == lastDepositDateConditions[0] %} selected {% endif %}>-6</option>
                                            <option value="-7" {% if "-7" == lastDepositDateConditions[0] %} selected {% endif %}>-7</option>
                                            <option value="-8" {% if "-8" == lastDepositDateConditions[0] %} selected {% endif %}>-8</option>
                                            <option value="-9" {% if "-9" == lastDepositDateConditions[0] %} selected {% endif %}>-9</option>
                                            <option value="-10" {% if "-10" == lastDepositDateConditions[0] %} selected {% endif %}>-10</option>
                                        </select>
                                        To (days ago)
                                        <select name="lastDepositDate_days_to">
                                            <option value="0" {% if "0" == lastDepositDateConditions[1] %} selected {% endif %}>Today</option>
                                            <option value="-1" {% if "-1" == lastDepositDateConditions[1] %} selected {% endif %}>-1</option>
                                            <option value="-2" {% if "-2" == lastDepositDateConditions[1] %} selected {% endif %}>-2</option>
                                            <option value="-3" {% if "-3" == lastDepositDateConditions[1] %} selected {% endif %}>-3</option>
                                            <option value="-4" {% if "-4" == lastDepositDateConditions[1] %} selected {% endif %}>-4</option>
                                            <option value="-5" {% if "-5" == lastDepositDateConditions[1] %} selected {% endif %}>-5</option>
                                            <option value="-6" {% if "-6" == lastDepositDateConditions[1] %} selected {% endif %}>-6</option>
                                            <option value="-7" {% if "-7" == lastDepositDateConditions[1] %} selected {% endif %}>-7</option>
                                            <option value="-8" {% if "-8" == lastDepositDateConditions[1] %} selected {% endif %}>-8</option>
                                            <option value="-9" {% if "-9" == lastDepositDateConditions[1] %} selected {% endif %}>-9</option>
                                            <option value="-10" {% if "-10" == lastDepositDateConditions[1] %} selected {% endif %}>-10</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr id="show_totalDeposited" {% if (attributesChecked['totalDeposited'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Total Deposited
                                    {% if (attributesChecked['totalDeposited'] is defined) %}
                                        <?php $totalDepositedConditions = explode(',', $attributes[$attributesChecked['totalDeposited_key']]->getConditions()); ?>
                                    {% else %}
                                        <?php $totalDepositedConditions = ['','']; ?>
                                    {% endif %}
                                </td>
                                <td>
                                    From <input type="text" name="totalDeposited_from" value="{{ totalDepositedConditions[0] }}" /> to <input type="text" name="totalDeposited_to" value="{{ totalDepositedConditions[1] }}" />
                                </td>
                            </tr>
                            <tr id="show_lastWithdrawal" {% if (attributesChecked['lastWithdrawal'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Last Withdrawal
                                </td>
                                <td>
                                    {% if (attributesChecked['lastWithdrawal'] is defined) %}
                                        <?php
                                            $lastWithdrawalConditions = explode(',', $attributes[$attributesChecked['lastWithdrawal_key']]->getConditions());
                                            if (strlen($lastWithdrawalConditions[0]) > 3) {
                                                $radioLastWithdrawal = 'dates';
                                            } else {
                                                $radioLastWithdrawal = 'days';
                                            }
                                        ?>
                                    {% else %}
                                        {% set lastWithdrawalConditions = ['',''] %}
                                        {% set radioLastWithdrawal = '' %}
                                    {% endif %}
                                    <div>
                                        <input type="radio" name="type_lastWithdrawal" value="dates" {% if radioLastWithdrawal == 'dates' %} checked {% endif %} />
                                        From <input type="text" name="lastWithdrawal_date_from" id="lastWithdrawal_date_from" {% if radioLastWithdrawal == 'dates' %} value="{{ lastWithdrawalConditions[0] }}" {% endif %} />
                                        To <input type="text" name="lastWithdrawal_date_to" id="lastWithdrawal_date_to" {% if radioLastWithdrawal == 'dates' %} value="{{ lastWithdrawalConditions[1] }}" {% endif %} />
                                    </div>
                                    <div>
                                        <input type="radio" name="type_lastWithdrawal" value="days" {% if radioLastWithdrawal == 'days' %} checked {% endif %} />
                                        From (days ago)
                                        <select name="lastWithdrawal_days_from">
                                            <option value="0" {% if "0" == lastWithdrawalConditions[0] %} selected {% endif %}>Today</option>
                                            <option value="-1" {% if "-1" == lastWithdrawalConditions[0] %} selected {% endif %}>-1</option>
                                            <option value="-2" {% if "-2" == lastWithdrawalConditions[0] %} selected {% endif %}>-2</option>
                                            <option value="-3" {% if "-3" == lastWithdrawalConditions[0] %} selected {% endif %}>-3</option>
                                            <option value="-4" {% if "-4" == lastWithdrawalConditions[0] %} selected {% endif %}>-4</option>
                                            <option value="-5" {% if "-5" == lastWithdrawalConditions[0] %} selected {% endif %}>-5</option>
                                            <option value="-6" {% if "-6" == lastWithdrawalConditions[0] %} selected {% endif %}>-6</option>
                                            <option value="-7" {% if "-7" == lastWithdrawalConditions[0] %} selected {% endif %}>-7</option>
                                            <option value="-8" {% if "-8" == lastWithdrawalConditions[0] %} selected {% endif %}>-8</option>
                                            <option value="-9" {% if "-9" == lastWithdrawalConditions[0] %} selected {% endif %}>-9</option>
                                            <option value="-10" {% if "-10" == lastWithdrawalConditions[0] %} selected {% endif %}>-10</option>
                                        </select>
                                        To (days ago)
                                        <select name="lastWithdrawal_days_to">
                                            <option value="0" {% if "0" == lastWithdrawalConditions[1] %} selected {% endif %}>Today</option>
                                            <option value="-1" {% if "-1" == lastWithdrawalConditions[1] %} selected {% endif %}>-1</option>
                                            <option value="-2" {% if "-2" == lastWithdrawalConditions[1] %} selected {% endif %}>-2</option>
                                            <option value="-3" {% if "-3" == lastWithdrawalConditions[1] %} selected {% endif %}>-3</option>
                                            <option value="-4" {% if "-4" == lastWithdrawalConditions[1] %} selected {% endif %}>-4</option>
                                            <option value="-5" {% if "-5" == lastWithdrawalConditions[1] %} selected {% endif %}>-5</option>
                                            <option value="-6" {% if "-6" == lastWithdrawalConditions[1] %} selected {% endif %}>-6</option>
                                            <option value="-7" {% if "-7" == lastWithdrawalConditions[1] %} selected {% endif %}>-7</option>
                                            <option value="-8" {% if "-8" == lastWithdrawalConditions[1] %} selected {% endif %}>-8</option>
                                            <option value="-9" {% if "-9" == lastWithdrawalConditions[1] %} selected {% endif %}>-9</option>
                                            <option value="-10" {% if "-10" == lastWithdrawalConditions[1] %} selected {% endif %}>-10</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr id="show_totalWithdrawal" {% if (attributesChecked['totalWithdrawal'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Total Withdrawal
                                    {% if (attributesChecked['totalWithdrawal'] is defined) %}
                                        <?php $totalWithdrawalConditions = explode(',', $attributes[$attributesChecked['totalWithdrawal_key']]->getConditions()); ?>
                                    {% else %}
                                        <?php $totalWithdrawalConditions = ['','']; ?>
                                    {% endif %}
                                </td>
                                <td>
                                    From <input type="text" name="totalWithdrawal_from" value="{{ totalWithdrawalConditions[0] }}" /> to <input type="text" name="totalWithdrawal_to" value="{{ totalWithdrawalConditions[1] }}" />
                                </td>
                            </tr>
                            <tr id="show_lastLoginDate" {% if (attributesChecked['lastLoginDate'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Last login date
                                    {% if (attributesChecked['lastLoginDate'] is defined) %}
                                        <?php
                                            $lastLoginDateConditions = explode(',', $attributes[$attributesChecked['lastLoginDate_key']]->getConditions());
                                            if (strlen($lastLoginDateConditions[0]) > 3) {
                                                $radioLastLoginDate = 'dates';
                                            } else {
                                                $radioLastLoginDate = 'days';
                                            }
                                        ?>
                                    {% else %}
                                        {% set lastLoginDateConditions = ['',''] %}
                                        {% set radioLastLoginDate = '' %}
                                    {% endif %}
                                </td>
                                <td>
                                    <div>
                                        <input type="radio" name="type_lastLoginDate" value="dates" {% if radioLastLoginDate == 'dates' %} checked {% endif %} />
                                        From <input type="text" name="lastLoginDate_date_from" id="lastLoginDate_date_from" {% if radioLastLoginDate == 'dates' %} value="{{ lastLoginDateConditions[0] }}" {% endif %} />
                                        To <input type="text" name="lastLoginDate_date_to" id="lastLoginDate_date_to" {% if radioLastLoginDate == 'dates' %} value="{{ lastLoginDateConditions[1] }}" {% endif %} />
                                    </div>
                                    <div>
                                        <input type="radio" name="type_lastLoginDate" value="days" {% if radioLastLoginDate == 'days' %} checked {% endif %} />
                                        From (days ago)
                                        <select name="lastLoginDate_days_from">
                                            <option value="0" {% if "0" == lastLoginDateConditions[0] %} selected {% endif %}>Today</option>
                                            <option value="-1" {% if "-1" == lastLoginDateConditions[0] %} selected {% endif %}>-1</option>
                                            <option value="-2" {% if "-2" == lastLoginDateConditions[0] %} selected {% endif %}>-2</option>
                                            <option value="-3" {% if "-3" == lastLoginDateConditions[0] %} selected {% endif %}>-3</option>
                                            <option value="-4" {% if "-4" == lastLoginDateConditions[0] %} selected {% endif %}>-4</option>
                                            <option value="-5" {% if "-5" == lastLoginDateConditions[0] %} selected {% endif %}>-5</option>
                                            <option value="-6" {% if "-6" == lastLoginDateConditions[0] %} selected {% endif %}>-6</option>
                                            <option value="-7" {% if "-7" == lastLoginDateConditions[0] %} selected {% endif %}>-7</option>
                                            <option value="-8" {% if "-8" == lastLoginDateConditions[0] %} selected {% endif %}>-8</option>
                                            <option value="-9" {% if "-9" == lastLoginDateConditions[0] %} selected {% endif %}>-9</option>
                                            <option value="-10" {% if "-10" == lastLoginDateConditions[0] %} selected {% endif %}>-10</option>
                                        </select>
                                        To (days ago)
                                        <select name="lastLoginDate_days_to">
                                            <option value="0" {% if "0" == lastLoginDateConditions[1] %} selected {% endif %}>Today</option>
                                            <option value="-1" {% if "-1" == lastLoginDateConditions[1] %} selected {% endif %}>-1</option>
                                            <option value="-2" {% if "-2" == lastLoginDateConditions[1] %} selected {% endif %}>-2</option>
                                            <option value="-3" {% if "-3" == lastLoginDateConditions[1] %} selected {% endif %}>-3</option>
                                            <option value="-4" {% if "-4" == lastLoginDateConditions[1] %} selected {% endif %}>-4</option>
                                            <option value="-5" {% if "-5" == lastLoginDateConditions[1] %} selected {% endif %}>-5</option>
                                            <option value="-6" {% if "-6" == lastLoginDateConditions[1] %} selected {% endif %}>-6</option>
                                            <option value="-7" {% if "-7" == lastLoginDateConditions[1] %} selected {% endif %}>-7</option>
                                            <option value="-8" {% if "-8" == lastLoginDateConditions[1] %} selected {% endif %}>-8</option>
                                            <option value="-9" {% if "-9" == lastLoginDateConditions[1] %} selected {% endif %}>-9</option>
                                            <option value="-10" {% if "-10" == lastLoginDateConditions[1] %} selected {% endif %}>-10</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr id="show_balance" {% if (attributesChecked['balance'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Balance
                                    {% if (attributesChecked['balance'] is defined) %}
                                        <?php $balanceConditions = explode(',', $attributes[$attributesChecked['balance_key']]->getConditions()); ?>
                                    {% else %}
                                        <?php $balanceConditions = ['','']; ?>
                                    {% endif %}
                                </td>
                                <td>
                                    From <input type="text" name="balance_from" value="{{ balanceConditions[0] }}" /> to <input type="text" name="balance_to" value="{{ balanceConditions[1] }}" />
                                </td>
                            </tr>
                            <tr id="show_inNotTrackingCode" {% if (attributesChecked['inNotTrackingCode'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    In/Not in Tracking Code
                                    {% if (attributesChecked['inNotTrackingCode'] is defined) %}
                                        <?php
                                            $inNotTrackingCodeConditions = explode('|', $attributes[$attributesChecked['inNotTrackingCode_key']]->getConditions());
                                            $inNotTrackingCodeIds = explode(',', $inNotTrackingCodeConditions[1]);
                                            if ($inNotTrackingCodeConditions[0] == 'In') {
                                                $radioInNotTrackingCode = 'In';
                                            } else {
                                                $radioInNotTrackingCode = 'NotIn';
                                            }
                                        ?>
                                    {% else %}
                                        {% set inNotTrackingCodeIds = '' %}
                                        {% set radioInNotTrackingCode = '' %}
                                    {% endif %}
                                </td>
                                <td>
                                    <input type="radio" name="inNotTrackingCode_type" value="In" {% if radioInNotTrackingCode == 'In' %} checked {% endif %} /> In &nbsp;
                                    <input type="radio" name="inNotTrackingCode_type" value="NotIn" {% if radioInNotTrackingCode == 'NotIn' %} checked {% endif %} /> Not in
                                    <br />
                                    <select multiple name="inNotTrackingCode[]" size="15" style="width: 300px;">
                                        {% for trackingCode in allTrackingCodes %}
                                            <option value="{{ trackingCode['id'] }}"  {% if trackingCode['id'] in inNotTrackingCodeIds %} selected {% endif %}>{{ trackingCode['name'] }}</option>
                                        {% endfor %}
                                    </select>
                                </td>
                            </tr>
                            <tr id="show_lotteriesPlayed" {% if (attributesChecked['lotteriesPlayed'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Lotteries played
                                    {% if (attributesChecked['lotteriesPlayed'] is defined) %}
                                        <?php $lotteriesPlayedIds = explode(',', $attributes[$attributesChecked['lotteriesPlayed_key']]->getConditions()); ?>
                                    {% else %}
                                        <?php $lotteriesPlayedIds = ''; ?>
                                    {% endif %}
                                </td>
                                <td>
                                    <select multiple name="lotteriesPlayed[]" size="5">
                                        {% for lottery in lotteries %}
                                            <option value="{{ lottery.getId() }}" {% if lottery.getId() in lotteriesPlayedIds %} selected {% endif %}>{{ lottery.getName() }}</option>
                                        {% endfor %}
                                    </select>
                                </td>
                            </tr>
                            <tr id="show_wagering" {% if (attributesChecked['wagering'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Wagering
                                    {% if (attributesChecked['wagering'] is defined) %}
                                        <?php $wageringConditions = explode(',', $attributes[$attributesChecked['wagering_key']]->getConditions()); ?>
                                    {% else %}
                                        <?php $wageringConditions = ['','']; ?>
                                    {% endif %}
                                </td>
                                <td>
                                    From <input type="text" name="wagering_from" value="{{ wageringConditions[0] }}" /> to <input type="text" name="wagering_to" value="{{ wageringConditions[1] }}" />
                                </td>
                            </tr>
                            <tr id="show_grossRevenue" {% if (attributesChecked['grossRevenue'] is not defined) %}class="display-none"{% endif %}>
                                <td>
                                    Gross Revenue
                                    {% if (attributesChecked['grossRevenue'] is defined) %}
                                        <?php $grossRevenueConditions = explode(',', $attributes[$attributesChecked['grossRevenue_key']]->getConditions()); ?>
                                    {% else %}
                                        <?php $grossRevenueConditions = ['','']; ?>
                                    {% endif %}
                                </td>
                                <td>
                                    From <input type="text" name="grossRevenue_from" value="{{ grossRevenueConditions[0] }}" /> to <input type="text" name="grossRevenue_to" value="{{ grossRevenueConditions[1] }}" />
                                </td>
                            </tr>
                        </table>
                        <br />
                        <p align="center">
                            <input type="button" value="Go Back" onclick="location='/admin/tracking/index'" class="btn btn-primary" />
                            &nbsp; &nbsp; &nbsp;
                            <input type="submit" value="Save" class="btn btn-primary" />
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

{% endblock %}