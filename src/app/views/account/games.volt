{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/css/account.css">
{% endblock %}
{% block bodyClass %}my-games{% endblock %}

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
           {% include "account/_nav.volt" %}
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
            <h2 class="h3">Ongoing Games</h2>
            <table class="ongoing cl table ui-responsive" data-role="table" data-mode="reflow">
                <thead>
                    <th class="date">
                        {{ language.translate("Game played") }}
                    </th>
                    <th class="duration">
                        {{ language.translate("Duration") }}
                    </th>
                    <th class="numbers">
                        {{ language.translate("Numbers played") }}
                    </th>
                    <th class="action">
                        {{ language.translate("Actions") }}
                    </th>
                </thead>
                <tbody>
                    <tr>
                        <td class="date">
                            <strong>{{ language.translate("Euromillions") }}</strong>
                            13 May 2015
                        </td>
                        <td class="duration"><strong>Next Friday</strong> Draw: 1</td>
                        <td class="numbers">
                            <div class="myCol">
                                05 08 24 25 32 <span class="star">08</span> <span class="star">10</span>
                            </div>
                        </td>
                        <td class="action"><a href="javascript:void(0);" class="btn blue">Edit <i class="ico ico-pencil"></i></a> <a href="javascript:void(0);" class="btn red">Delete <i class="ico ico-cross"></i></a></td>
                    </tr>
                    <tr>
                        <td class="date">
                            <strong>{{ language.translate("Euromillions") }}</strong>
                            14 May 2015
                        </td>
                        <td class="duration"><strong>4 weeks</strong> Draws: 8</td>
                        <td class="numbers">
                            <div class="myCol">
                                02 03 04 05 07 <span class="star">08</span> <span class="star">10</span>
                            </div>
                        </td>
                        <td class="action"><a href="javascript:void(0);" class="btn blue">Edit <i class="ico ico-pencil"></i></a> <a href="javascript:void(0);" class="btn red">Delete <i class="ico ico-cross"></i></a></td>
                    </tr>
                    <tr>
                        <td class="date">
                            <strong>{{ language.translate("Euromillions") }}</strong>
                            18 May 2015
                        </td>
                        <td class="duration"><strong>Ongoing</strong> Play only when the Jackpot reach 75 millions &euro;</td>
                        <td class="numbers">
                            <div class="myCol">
                                05 13 24 35 41 <span class="star">01</span> <span class="star">09</span>
                            </div>
                        </td>
                        <td class="action"><a href="javascript:void(0);" class="btn blue">Edit <i class="ico ico-pencil"></i></a> <a href="javascript:void(0);" class="btn red">Delete <i class="ico ico-cross"></i></a></td>
                    </tr>
                </tbody>
            </table>

            <h2 class="h3">Past Games</h2>
            <table id="game-history" class="cl table ui-responsive" data-role="table" data-mode="reflow">
                <thead>
                    <tr>
                        <th class="date">
                            {{ language.translate("Game played") }}
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
                        <td class="date">
                            <strong>{{ language.translate("Euromillions") }}</strong>
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
                        <td class="date">
                            <strong>{{ language.translate("Euromillions") }}</strong>
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

            {% include "account/_paging.volt" %}
        </div>
    </div>
</main>
{% endblock %}