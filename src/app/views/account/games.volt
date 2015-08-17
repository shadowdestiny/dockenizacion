{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/account.css">
{% endblock %}
{% block bodyClass %}my-games{% endblock %}

{% block template_scripts %}

{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="nav box-basic">
           {% set activeSubnav='{"myClass": "games"}'|json_decode %}
           {% include "account/nav.volt" %}
        </div>
        <div class="box-basic content">
            <h1 class="h1 title">{{ language.translate("My Games") }}</h1>

            *Without Data*

            <div class="box info">
                <span class="ico ico-info"></span>
                <span class="txt">
                    {{ language.translate("You didn't play any games yet.") }} <a href="javascript:void(0);">{{ language.translate("Play now and start to win.") }}</a>
                </span>
            </div>

            <a href="javascript:void(0)" class="no-data img">
                <div class="txt">
                    <span class="h1">
                        {{ language.translate("Dream to Win<br>
                        a real treasure awaits!") }}
                    </span>
                    
                    <span class="jackpot-txt">{{ language.translate("Jackpot this week") }}</span>
                    
                    {% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
                    {% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
                    <span class="btn blue big" >{{ language.translate("Play now") }}</span>
                </div>
            </a>

            *With Data*

            <table id="game-history" class="cl table ui-responsive" data-role="table" data-mode="reflow">
                <thead>
                    <tr>
                        <th class="id">
                            {{ language.translate("Id") }} <span class="ico ico-triangle-down"></span>
                        </th>
                        <th class="game">
                            {{ language.translate("Games") }}
                        </th>
                        <th class="date">
                            {{ language.translate("Date") }}
                        </th>
                        <th class="numbers">
                            {{ language.translate("Numbers <span class='desktop'>played</span>") }}
                        </th>
                        <th class="action">
                            {{ language.translate("Actions") }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="id">
                            #2
                        </td>
                        <td class="game">
                            {{ language.translate("Euromillions") }}
                        </td>
                        <td class="date">
                            16 May 2015
                        </td>
                        <td class="numbers">
                            <div class="myCol">
                                02 03 04 05 07 <span class="star">08</span> <span class="star">10</span>
                            </div>
                        </td>
                        <td class="action">
                            <a href="javascript:void(0);" class="btn blue">{{ language.translate("Play it <span class='desktop'>again</span> for") }} 2,35 &euro;</a>
                        </td>
                    </tr>
                    <tr class="special">
                        <td class="id">
                            #1
                        </td>
                        <td class="game">
                            {{ language.translate("Euromillions") }}
                        </td>
                        <td class="date">
                            12 May 2015
                        </td>
                        <td class="numbers">
                            <div class="myCol">
                                02 03 04 05 07 22 55 <span class="star">08</span> <span class="star">10</span>
                            </div>
                        </td>
                        <td class="action">
                            <a href="javascript:void(0);" class="btn blue">{{ language.translate("Play it <span class='desktop'>again</span> for") }} 2,35 &euro;</a>
                        </td>
                    </tr>
                </tbody>
            </table>

            {% include "account/paging.volt" %}
        </div>
    </div>
</main>
{% endblock %}