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

{% block body %}
    <main id="content">
        <div class="wrapper">
            <div class="nav box-basic">
                {% set activeSubnav='{"myClass": "transaction"}'|json_decode %}
                {% include "account/_nav.volt" %}
            </div>

            <div class="box-basic content">
                <h1 class="h1 title">{{ language.translate("Transaction") }}</h1>

                <div class="box success">
                    <svg class="ico v-checkmark"><use xlink:href="/w/svg/icon.svg#v-checkmark"></use></svg>
                    <span class="txt">{{ language.translate("Transaction successful.")}} <span class="info">{{ language.translate("You just added &euro; 300 to your balance.")}}</span></span> 
                </div>

                <table class="cl table ui-responsive" data-role="table" data-mode="reflow">
                    <thead>
                        <tr>
                            <th class="date">{{ language.translate("Date")}}</th>
                            <th class="type">{{ language.translate("Transaction")}}</th>
                            <th class="movement">{{ language.translate("Movement")}}</th>
                            <th class="wallet">{{ language.translate("Wallet")}}</th>
                            <th class="winnings">{{ language.translate("Winnings")}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="date">16 May 2015</td>
                            <td class="type">{{ language.translate("Played lotto")}}</td>
                            <td class="movement">&euro; -15</td>
                            <td class="wallet">&euro; 75</td>
                            <td class="winnings">&euro; 75</td>
                        </tr>
                        <tr>
                            <td class="date">16 May 2015</td>
                            <td class="type">{{ language.translate("Added funds")}}</td>
                            <td class="movement">&euro; 35</td>
                            <td class="wallet">&euro; 75</td>
                            <td class="winnings">&euro; 75</td>
                        </tr>
                        <tr>
                            <td class="date">16 May 2015</td>
                            <td class="type">{{ language.translate("Withdrawn")}}</td>
                            <td class="movement">&euro; -15</td>
                            <td class="wallet">&euro; 75</td>
                            <td class="winnings">0</td>
                        </tr>
                        <tr>
                            <td class="date">16 May 2015</td>
                            <td class="type">{{ language.translate("Winnings") }}</td>
                            <td class="movement">&euro; -15</td>
                            <td class="wallet">&euro; 75</td>
                            <td class="winnings">&euro; 75</td>
                        </tr>
                    </tbody>
                </table>

                {% include "account/_paging.volt" %}
            </div>
        </div>
    </main>
{% endblock %}