{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
    <link rel="stylesheet" href="/a/css/pagination.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.webui-popover/1.2.13/jquery.webui-popover.min.css">
{% endblock %}
{% block bodyClass %}transaction{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}
    <script src="/w/js/mobileFix.min.js"></script>
    <script>

        $(function () {
            var hasContent = true;
            $('#table_transactions tbody tr').each(function () {
                var showPopover = $(this).find('td:eq(2)').data('trans');
                var idTransaction = $(this).data('id');
                var title = '';
                if (typeof showPopover == 'undefined') return;
                $(this).webuiPopover({
                    type: 'async',
                    cache: true,
                    url: '/ajax/transaction-detail/obtain/' + idTransaction,
                    trigger: 'manual',
                    content: function (data) {
                        console.log(data);
                        if (data != 'null') {
                            var dataJson = JSON.parse(data);
                            var title = (dataJson[0].type == 'ticket_purchase') ? "{{ language.translate("transaction_purchase_head") }}" : "{{ language.translate("transaction_win_head") }}";
                            var content = $('<div>');
                            content.append('<div style="background-color: #B75D84;width:100%;color:#FFFFDF">' + title + '</div>');
                            content.append('EuroMillions: ' + " " + dataJson[0].drawDate + '<br>');
                            if (dataJson[0].type == 'ticket_purchase') {
                                content.append('<ul>');
                                $.each(dataJson, function (i, draw) {
                                    content.append('<li><span class="num">' + draw.regularNumbers + '</span> <span style="color:#ebb019"> ' + draw.luckyNumbers + '</span></li>');
                                });
                                content.append('</ul>');
                                title = 'Ticket Purchase';
                            } else if (dataJson[0].type == 'winning_receive') {
                                content.append("{{ language.translate("transaction_win_numbers") }}: " + dataJson[0].draw + '</br>');
                                content.append("{{ language.translate("transaction_win_lines") }}:</br>");
                                content.append('<ul>');
                                $.each(dataJson, function (i, draw) {
                                    if (draw.matchNumbers == null) draw.matchNumbers = '';
                                    var matchNumbers = draw.matchNumbers.split(',');
                                    if (draw.matchLucky == null) draw.matchLucky = '';
                                    var matchStars = draw.matchLucky.split(',');
                                    var splitNumbers = draw.regularNumbers.split(',');
                                    var splitStars = draw.luckyNumbers.split(',');
                                    $.each(splitNumbers, function (i, mNumber) {
                                        if (matchNumbers.indexOf(mNumber) != -1) {
                                            content.append('<span style="color:red">' + mNumber + ' ');
                                        } else {
                                            content.append(mNumber + ' ');
                                        }
                                    });
                                    $.each(splitStars, function (i, mStar) {
                                        if (matchStars.indexOf(mStar) != -1) {
                                            content.append('<span style="color:red">' + mStar + ' ');
                                        } else {
                                            content.append(mStar + ' ');
                                        }
                                    });
                                });
                                content.append('</ul>');
                                title = 'Winning';
                            }
                            content.append('</div>');
                            return content.html();
                        } else {
                            hasContent = false;
                        }
                    },
                    title: title
                });
                $(this).on('mouseenter', function () {
                    $(this).webuiPopover('show');
                });
                $(this).on('mouseleave', function () {
                    $(this).webuiPopover('hide');
                });

            });
        });
    </script>
{% endblock %}

{% block body %}
    <main id="content" class="account-page">
        <div class="wrapper">
            <div class="nav">
                {% set activeSubnav='{"myClass": "transaction"}'|json_decode %}
                {% include "account/_nav.volt" %}
            </div>

            <div class="content">
                {#<h1 class="h1 title">{{ language.translate("transaction_head") }}</h1>#}

                {#<div class="box success">#}
                {#<svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>#}
                {#<span class="txt">{{ language.app("Transaction successful.")}} <span class="info">{{ language.app("You just added &euro; 300 to your balance.")}}</span></span>#}
                {#</div>#}



                {#TODO : Add real variables here#}
                {#Clear html start#}
                <table id="table_transactions" class="table_transactions_v2" data-role="table" data-mode="">
                    <thead>
                    <tr class="row-common">
                        <th class="date">
                            <p>
                                Date
                            </p>

                        </th>
                        <th class="lottery">
                            <p>
                                lottery
                            </p>

                        </th>
                        <th class="type">
                            <p>
                                Type
                            </p>

                        </th>
                        <th class="amount">
                            <p>
                                Amount
                            </p>

                        </th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr class="row-common tr-transactions-" data-id="">
                        <td class="date">
                            <p>
                                24/04/17 19:00
                            </p>
                        </td>

                        <td class="lottery">
                            <p>
                                Euro millions
                            </p>
                        </td>

                        <td class="type">
                            <p>
                                Deposit
                            </p>
                        </td>

                        <td class="amount">
                            <p>
                                +€50.05
                            </p>
                        </td>
                    </tr>
                    <tr class="row-mobile">
                        <td colspan="4">
                            <p>
                                Loterry: Euro Millions
                            </p>
                            <p>
                                Type: Ticket Purchase
                            </p>
                        </td>
                    </tr>

                    <tr class="row-common tr-transactions-" data-id="">
                        <td class="date">
                            <p>
                                24/04/17 19:00
                            </p>
                        </td>

                        <td class="lottery">
                            <p>
                                Euro millions
                            </p>
                        </td>

                        <td class="type">
                            <p>
                                Deposit
                            </p>
                        </td>

                        <td class="amount">
                            <p>
                                +€50.05
                            </p>
                        </td>
                    </tr>
                    <tr class="row-mobile">
                        <td colspan="4">
                            <p>
                                Loterry: Euro Millions
                            </p>
                            <p>
                                Type: Ticket Purchase
                            </p>
                        </td>
                    </tr>

                    <tr class="row-common tr-transactions-" data-id="">
                        <td class="date">
                            <p>
                                24/04/17 19:00
                            </p>
                        </td>

                        <td class="lottery">
                            <p>
                                Euro millions
                            </p>
                        </td>

                        <td class="type">
                            <p>
                                Deposit
                            </p>
                        </td>

                        <td class="amount">
                            <p>
                                +€50.05
                            </p>
                        </td>
                    </tr>
                    <tr class="row-mobile">
                        <td colspan="4">
                            <p>
                                Loterry: Euro Millions
                            </p>
                            <p>
                                Type: Ticket Purchase
                            </p>
                        </td>
                    </tr>

                    </tbody>
                </table>
                {#Clear html end#}


                <table id="table_transactions" class="cl table ui-responsive" data-role="table" data-mode="reflow">
                    <thead>
                    <tr>
                        <th class="date">{{ language.translate("transaction_date") }}</th>
                        <th class="type">{{ language.translate("transaction_type") }}</th>
                        <th class="movement">{{ language.translate("transaction_price") }}</th>
                        {#<th class="movement">{{ language.translate("Movement")}}</th>#}
                        {#<th class="movement">{{ language.translate("Pending Balance Movement")}}</th>#}
                        <th class="wallet">{{ language.translate("transaction_balance") }}</th>

                        <th class="wallet">{{ language.translate("transaction_SubsBalance") }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    {% for transaction in transactionCollection %}
                        <tr class="tr-transactions-{{ transaction.id }}" data-id="{{ transaction.id }}">
                            <td class="date">{{ transaction.date }}</td>
                            <td class="type">
                                {% if transaction.transactionName == 'Winning Withdraw' %} {{ language.translate("transaction_type_withdraw") }}
                                {% elseif transaction.transactionName == 'Ticket Purchase' %} {{ language.translate("transaction_type_purchase") }}
                                {% elseif transaction.transactionName == 'Automatic Purchase' %} {{ language.translate("transaction_type_purchase_aut") }}
                                {% elseif transaction.transactionName == 'Subscription Deposit' %} {{ language.translate("transaction_type_subs_deposit") }}
                                {% elseif transaction.transactionName == 'Deposit' %} {{ language.translate("transaction_type_deposit") }}
                                {% elseif transaction.transactionName == 'Winnings Received' %} {{ language.translate("transaction_type_win") }}
                                {% else %} {{ transaction.transactionName }}
                                {% endif %}
                            </td>
                            <td class="type" {% if transaction.transactionName == 'Winning Withdraw' or transaction.transactionName == 'Ticket Purchase' %} data-trans="" style="color:#c22"{% endif %} >{{ transaction.ticketPrice }}</td>
                            {#<td class="movement" {% if transaction.transactionName == 'Winning Withdraw' or transaction.transactionName == 'Ticket Purchase' %} data-trans="" style="color:#c22"{% endif %} {% if transaction.transactionName == 'Winnings Received'%}data-trans="" {% endif %}>#}
                            {#{{ transaction.movement }}#}
                            {#</td>#}
                            {#<td class="wallet">{{ transaction.pendingBalanceMovement }}</td>#}
                            <td class="wallet">{{ transaction.balance }}
                                (<span {% if transaction.transactionName == 'Winning Withdraw' or transaction.transactionName == 'Ticket Purchase' %} data-trans="" style="color:#c22"{% endif %} {% if transaction.transactionName == 'Deposit' %}style="color:green" {% endif %}>{{ transaction.movement }} </span>)
                            </td>

                            <td class="wallet">{{ transaction.pendingBalance }}
                                (<span {% if transaction.transactionName == 'Winning Withdraw' or (transaction.transactionName == 'Automatic Purchase' and transaction.pendingBalanceMovement != '€0.00' ) or (transaction.transactionName == 'Ticket Purchase' and transaction.pendingBalanceMovement != '€0.00' and transaction.movement == '€0.00') or (transaction.transactionName == 'Ticket Purchase' and transaction.pendingBalanceMovement != '€0.00' and transaction.movement != '€0.00' and transaction.ticketPrice == '€0.00') %} data-trans="" style="color:#c22"{% endif %} {% if transaction.transactionName == 'Subscription Deposit' or (transaction.transactionName == 'Ticket Purchase' and transaction.pendingBalanceMovement != '€0.00' and transaction.movement != '€0.00') %}style="color:green" {% endif %}>{{ transaction.pendingBalanceMovement }} </span>)
                            </td>
                        </tr>
                    {% endfor %}
                    </tbody>
                </table>
                {{ paginator_view }}
                {#{% include "account/_paging.volt" %}#}
            </div>
        </div>
    </main>
{% endblock %}