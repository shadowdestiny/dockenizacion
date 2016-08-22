{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
    <link rel="stylesheet" href="/a/css/pagination.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.webui-popover/1.2.1/jquery.webui-popover.min.css">
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
//            var id = 0;
//            $('tr').on('mouseenter',function(){
//                id = $(this).data('id');
//                console.log(id);
//            });
//
//                $('#table_transactions').webuiPopover({
//                    type: 'async',
//                    url: '/ajax/transaction-detail/obtain/'+id,
//                    content: function(data){
//
//                    },
//                    trigger: 'hover'
//                });
            var idTransaction = 2;

            $('#table_transactions tbody tr').webuiPopover({
                type: 'async',
                url: '/ajax/transaction-detail/obtain/'+idTransaction,
                content: function(data){
                },
                trigger: 'hover'
            });
//
//            $('tr').on('mouseenter',function(e){
//                idTransaction = $(this).data('id');
//                $('#table_transactions tbody tr').webuiPopover('show');
//            });
//
//            $('tr').on('mouseleave',function(e){
//                $('#table_transactions tbody tr').webuiPopover('hide');
//            });
//

        })
//        type: 'async',
//                url: '/ajax/transaction-detail/obtain/'+id,
//                content: function(data){
//            console.log(data);
//        }
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
                            <tr data-id="{{ transaction.id }}">
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