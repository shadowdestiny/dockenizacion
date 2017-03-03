<h3 class="h4">Withdrawals</h3>
{% if userWithdrawals is empty %}
    This player doesn't have withdrawals
{% else %}
    <table class="table-program" width="100%">
        <thead>
        <tr class="special">
            <th>
                Contact detail
            </th>
            <th>
                Request date
            </th>
            <th>
                Account details
            </th>
            <th>
                Address
            </th>
            <th>
                Amt
            </th>
            <th>
                State
            </th>
            <!-- th>
                Change State
            </th -->
        </tr>
        </thead>
        <tbody>
            {% for userWithdrawal in userWithdrawals %}
                <tr>
                    <td align="center">
                        {{ user.getEmail() }}
                    </td>
                    <td align="center">
                        {{ userWithdrawal['date'] }}
                    </td>
                    <td align="center">
                        Name: {{ user.getBankUserName() }} {{ user.getBankSurname() }}<br />
                        Bank Name: {{ user.getBankName() }}<br />
                        IBAN/Acc No:: {{ user.getBankAccount() }}<br />
                        BIC/Switft: {{ user.getBankSwift() }}<br />
                    </td>
                    <td align="center">
                        Address: {{ user.getStreet() }}<br />
                        City: {{ user.getCity() }}<br />
                        Post Code: {{ user.getZip() }}<br />
                        Country: {{ countryList[user.getCountry()] }}<br />
                    </td>
                    <td align="center">
                        {{ userWithdrawal['amount'] }} €
                    </td>
                    <td align="center">
                        {{ userWithdrawal['state'] }}
                    </td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
{% endif %}