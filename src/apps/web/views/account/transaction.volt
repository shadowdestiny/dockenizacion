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
            {% include "account/_breadcrumbs.volt" %}
            <div class="nav">
                {% set activeSubnav='{"myClass": "transaction"}'|json_decode %}
                <div class="dashboard-menu--desktop">
                    {% include "account/_nav.volt" %}
                </div>

                <div class="dashboard-menu--mobile--back">
                    <a href="/account/wallet">
                        {{ language.translate("myAccount_transactions") }}
                    </a>
                </div>
            </div>

            <div class="content">
                <table id="table_transactions" class="table_transactions_v2" data-role="table" data-mode="">
                    <thead>
                    <tr class="row-common">
                        <th class="date">
                            <p>
                                {{ language.translate("transaction_date") }}
                            </p>

                        </th>
                        <th class="lottery">
                            <p>
                                {{ language.translate("tickets_past_lotto") }}
                            </p>

                        </th>
                        <th class="type">
                            <p>
                                {{ language.translate("transaction_type") }}
                            </p>

                        </th>
                        <th class="amount">
                            <p>
                                {{ language.translate("mainprizes_column3") }}
                            </p>

                        </th>
                    </tr>
                    </thead>

                    <tbody>

                {% for transaction in transactionCollection %}
                    <tr class="row-common tr-transactions-" data-id="" {% if transaction.isErrorTransaction() %} style="border: 2px solid red;" {% endif %}>
                        <td class="date">
                            <p>
                                {{ transaction.date.format(language.translate('dateformat')) }}
                            </p>
                        </td>

                        <td class="lottery">
                            <p>
                                {% if (not(transaction.lotteryId is empty)) %}
                                    {{ transaction.lotteryId }}
                                {% endif %}
                            </p>
                        </td>

                        <td class="type">
                            <p>
                                {% if transaction.transactionName == 'Winning Withdraw' %} {{ language.translate("transaction_type_withdraw") }} ( {{ transaction.status }} )
                                {% elseif transaction.transactionName == 'Ticket Purchase' %} {{ language.translate("transaction_type_purchase") }}
                                {% elseif transaction.transactionName == 'Automatic Purchase' %} {{ language.translate("transaction_type_purchase_aut") }}
                                {% elseif transaction.transactionName == 'Subscription Deposit' %} {{ language.translate("transaction_type_subs_deposit") }}
                                {% elseif transaction.transactionName == 'Deposit' %} {{ language.translate("transaction_type_deposit") }}
                                {% elseif transaction.transactionName == 'Winnings Received' %} {{ language.translate("transaction_type_win") }}
                                {% else %} {{ transaction.transactionName }}
                                {% endif %}
                                {% if transaction.isErrorTransaction() %} Not Processed {% endif %}
                            </p>
                        </td>

                        <td class="amount">
                            <p>
								{% if transaction.isErrorTransaction() %} {{symbol}}0.00
								{% else %}
									{% if transaction.transactionName == 'Ticket Purchase' %}{{ transaction.ticketPrice }}
                                	{% elseif transaction.transactionName == 'Subscription Deposit'%}+{{ transaction.pendingBalanceMovement }}
                                    {% elseif transaction.transactionName == 'Automatic Purchase' %}{{ transaction.pendingBalanceMovement }}
                                    {% else %}{{ transaction.movement }}
                                    {% endif %}
								{% endif %}
                            </p>
                        </td>
                    </tr>
                    <tr class="row-mobile">
                        <td colspan="4">
                            <p>
                                Loterry: {% if (not(transaction.lotteryId is empty)) %}
                                    {{ language.translate('lottery_id_' ~ transaction.lotteryId) }}
                                {% endif %}
                            </p>
                            <p>
                                {{ language.translate("transaction_type") }}: {% if transaction.transactionName == 'Winning Withdraw' %} {{ language.translate("transaction_type_withdraw") }}
                                {% elseif transaction.transactionName == 'Ticket Purchase' %} {{ language.translate("transaction_type_purchase") }}
                                {% elseif transaction.transactionName == 'Automatic Purchase' %} {{ language.translate("transaction_type_purchase_aut") }}
                                {% elseif transaction.transactionName == 'Subscription Deposit' %} {{ language.translate("transaction_type_subs_deposit") }}
                                {% elseif transaction.transactionName == 'Deposit' %} {{ language.translate("transaction_type_deposit") }}
                                {% elseif transaction.transactionName == 'Winnings Received' %} {{ language.translate("transaction_type_win") }}
                                {% else %} {{ transaction.transactionName }}
                                {% endif %}
                            </p>
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