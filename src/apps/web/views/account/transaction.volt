{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
    <link rel="stylesheet" href="/a/css/pagination.css">
    <link rel="stylesheet" href="/w/css/vendor/tipped.css">
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
            $('#table_transactions tbody tr').on('mouseenter',function(e){
                var idTransaction = $(this).data('id');
                $.ajax({
                    url:'/ajax/transaction-detail/obtain/'+idTransaction,
                    success: function(data) {
                        if(data != 'null') {
                            var dataJson = JSON.parse(data);
                            var content = $('<div>');
                            content.append('EuroMillions: ' + " " + dataJson[0].drawDate + '<br>');
                            content.append('<ul>');
                            $.each(dataJson,function(i,draw){
                               content.append('<li>'+ draw.regularNumbers + " " + draw.luckyNumbers + '</li>');
                            });
                            content.append('</ul>');
                            content.append('</div>');
                            Tipped.create('.tr-transactions-'+idTransaction, content,
                                    {
                                        skin: 'light',
                                        title: 'Transaction Details - Ticket Purchase'
                                    }
                            );
                        }
                    }
                })
            }).on('mouseleave', function(e){
                var idTransaction = $(this).data('id');
                //Tipped.remove('.tr-transactions-'+idTransaction);
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
                            <th class="movement">{{ language.translate("Movement")}}</th>
                            <th class="wallet">{{ language.translate("Balance")}}</th>
                        </tr>
                    </thead>
                    <tbody>

                        {% for transaction in transactionCollection %}
                            <tr class="tr-transactions-{{ transaction.id }}" data-id="{{ transaction.id }}">
                                <td class="date">{{ transaction.date }}</td>
                                <td class="type">{{ transaction.transactionName }}</td>
                                <td class="movement" {% if transaction.transactionName == 'Winning Withdraw' or transaction.transactionName == 'Ticket Purchase' %}style="color:#c22"{% endif %}>
                                    {{ transaction.movement }}
                                </td>
                                <td class="wallet">{{ transaction.balance }}</td>
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