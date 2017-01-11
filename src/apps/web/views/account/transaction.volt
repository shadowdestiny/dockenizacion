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

        $(function(){
            var hasContent=true;
            $('#table_transactions tbody tr').each(function() {
                var showPopover = $(this).find('td:eq(2)').data('trans');
                var idTransaction = $(this).data('id');
                var title='';
                if(typeof showPopover == 'undefined') return;
                $(this).webuiPopover({
                    type: 'async',
                    cache: true,
                    url: '/ajax/transaction-detail/obtain/' + idTransaction,
                    trigger: 'manual',
                    content: function (data) {
                        console.log(data);
                        if (data != 'null') {
                            var dataJson = JSON.parse(data);
                            var title = (dataJson[0].type == 'ticket_purchase') ? 'Transaction Details - Ticket Purchase' : 'Transaction Details - Draw Winnings';
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
                                content.append('Winning Numbers: ' + dataJson[0].draw + '</br>');
                                content.append('Winning Lines:</br>');
                                content.append('<ul>');
                                $.each(dataJson, function (i, draw) {
                                    if(draw.matchNumbers == null) draw.matchNumbers = '';
                                    var matchNumbers = draw.matchNumbers.split(',');
                                    if(draw.matchLucky == null) draw.matchLucky = '';
                                    var matchStars = draw.matchLucky.split(',');
                                    var splitNumbers = draw.regularNumbers.split(',');
                                    var splitStars = draw.luckyNumbers.split(',');
                                    $.each(splitNumbers,function(i,mNumber){
                                        if(matchNumbers.indexOf(mNumber) != -1) {
                                            content.append('<span style="color:red">' + mNumber + ' ');
                                        } else {
                                            content.append(mNumber + ' ');
                                        }
                                    });
                                    $.each(splitStars,function(i,mStar){
                                        if(matchStars.indexOf(mStar) != -1) {
                                            content.append('<span style="color:red">' + mStar + ' ');
                                        } else {
                                            content.append(mStar + ' ');
                                        }
                                    });
                                });
                                content.append('</ul>');
                                title='Winning';
                            }
                            content.append('</div>');
                            return content.html();
                        } else {
                            hasContent=false;
                        }
                    },
                    title: title
                });
                $(this).on('mouseenter',function(){
                    $(this).webuiPopover('show');
                });
                $(this).on('mouseleave',function(){
                    $(this).webuiPopover('hide');
                });

            });
        });
    </script>
{% endblock %}

{% block body %}
    <main id="content">
        <div class="wrapper">
            <div class="nav box-basic">
                {% set activeSubnav='{"myClass": "transaction"}'|json_decode %}
                {% include "account/_nav.volt" %}
            </div>

            <div class="box-basic content">
                <h1 class="h1 title">{{ language.translate("Transaction") }}</h1>

                {#<div class="box success">#}
                    {#<svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>#}
                    {#<span class="txt">{{ language.app("Transaction successful.")}} <span class="info">{{ language.app("You just added &euro; 300 to your balance.")}}</span></span>#}
                {#</div>#}

                <table id="table_transactions" class="cl table ui-responsive" data-role="table" data-mode="reflow">
                    <thead>
                        <tr>
                            <th class="date">{{ language.translate("Date")}}</th>
                            <th class="type">{{ language.translate("Transaction")}}</th>
                            <th class="movement">{{ language.translate("Ticket Price")}}</th>
                            {#<th class="movement">{{ language.translate("Movement")}}</th>#}
                            {#<th class="movement">{{ language.translate("Pending Balance Movement")}}</th>#}
                            <th class="wallet">{{ language.translate("Balance")}}</th>

                            <th class="wallet">{{ language.translate("Pending Balance")}}</th>
                        </tr>
                    </thead>
                    <tbody>

                        {% for transaction in transactionCollection %}
                            <tr class="tr-transactions-{{ transaction.id }}" data-id="{{ transaction.id }}">
                                <td class="date">{{ transaction.date }}</td>
                                <td class="type">{{ transaction.transactionName }}</td>
                                <td class="type"  {% if transaction.transactionName == 'Winning Withdraw' or transaction.transactionName == 'Ticket Purchase' %} data-trans="" style="color:#c22"{% endif %} >{{ transaction.ticketPrice }}</td>
                                {#<td class="movement" {% if transaction.transactionName == 'Winning Withdraw' or transaction.transactionName == 'Ticket Purchase' %} data-trans="" style="color:#c22"{% endif %} {% if transaction.transactionName == 'Winnings Received'%}data-trans="" {% endif %}>#}
                                    {#{{ transaction.movement }}#}
                                {#</td>#}
                                {#<td class="wallet">{{ transaction.pendingBalanceMovement }}</td>#}
                                <td class="wallet" >{{ transaction.balance }} (<span {% if transaction.transactionName == 'Winning Withdraw' or transaction.transactionName == 'Ticket Purchase' %} data-trans="" style="color:#c22"{% endif %} {% if transaction.transactionName == 'Deposit'%}style="color:green" {% endif %}>{{ transaction.movement }} </span>) </td>

                                <td class="wallet">{{ transaction.pendingBalance }} (<span {% if transaction.transactionName == 'Winning Withdraw' or transaction.transactionName == 'Automatic Purchase'%} data-trans="" style="color:#c22"{% endif %} {% if transaction.transactionName == 'Subscription Deposit' or transaction.transactionName == 'Ticket Purchase'%}style="color:green" {% endif %}>{{ transaction.pendingBalanceMovement }} </span>)</td>
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