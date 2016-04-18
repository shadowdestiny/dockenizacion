{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
{% endblock %}
{% block bodyClass %}transaction{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}

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
                    {#<span class="txt">{{ language.translate("Transaction successful.")}} <span class="info">{{ language.translate("You just added &euro; 300 to your balance.")}}</span></span> #}
                {#</div>#}

                <table class="cl table ui-responsive" data-role="table" data-mode="reflow">
                    <thead>
                        <tr>
                            <th class="date">{{ language.translate("Date")}}</th>
                            <th class="type">{{ language.translate("Transaction")}}</th>
                            <th class="movement">{{ language.translate("Movement")}}</th>
                            <th class="wallet">{{ language.translate("Balance")}}</th>
                            <th class="winnings">{{ language.translate("Winnings")}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% for transaction in transactionCollection %}
                            <tr>
                                <td class="date">{{ transaction.date }}</td>
                                <td class="type">{{ transaction.transactionName }}</td>
                                <td class="movement">{{ transaction.movement }}</td>
                                <td class="wallet">{{ transaction.balance }}</td>
                                <td class="winnings">{{ transaction.winnings }}</td>
                            </tr>
                        {% endfor %}
                    </tbody>
                </table>

                {% include "account/_paging.volt" %}
            </div>
        </div>
    </main>
{% endblock %}