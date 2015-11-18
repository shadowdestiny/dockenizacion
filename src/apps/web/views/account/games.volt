{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
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


                {% if my_games_actives is empty or my_games_inactives is empty %}
                    <div class="box info">
                        <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                        <span class="txt">
                            {{ language.translate("You didn't play any games yet.") }} <a href="/play">{{ language.translate("Play now and start to win.") }}</a>
                        </span>
                    </div>

                    <a href="/play" class="no-data img">
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
                {% endif %}

                {% if my_games_actives is empty %}

                {% else %}
                    <h2 class="h3">Present Games</h2>
                    <table class="present cl table ui-responsive" data-role="table" data-mode="reflow">
                        <thead>
                            <th class="date">
                                {{ language.translate("Game played") }}
                            </th>
                            <th class="when">
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
                            {% for game in my_games_actives %}
                                <tr>
                                    <td class="date">
                                        <strong>{{ language.translate("Euromillions") }}</strong>
                                        {{ game.startDrawDate }}
                                    </td>
                                    <td class="duration">
                                        <strong>{{ game.duration }}</strong>
                                        (8 {{ language.translate("draws") }})
                                    </td>
                                    <td class="numbers">
                                        <div class="myCol">
                                            {#
                                                Commented because, every number need to be wrapped

                                                {{ game.regular_numbers }} 
                                                <span class="star">{{ game.lucky_numbers }}</span>
                                            #}
                                            <span class="num">1</span>
                                            <span class="num">2</span>
                                            <span class="num">30</span>
                                            <span class="num">37</span>
                                            <span class="num">49</span>
                                            <span class="num star">7</span>
                                            <span class="num star">11</span>
                                        </div>
                                    </td>
                                    <td class="action">
                                        <a href="javascript:void(0);" class="btn blue">Edit <svg class="ico v-pencil"><use xlink:href="/w/svg/icon.svg#v-pencil"></use></svg></a>
                                        <a href="javascript:void(0);" class="btn red">Delete <svg class="ico v-cross"><use xlink:href="/w/svg/icon.svg#v-cross"></use></svg></a>
                                    </td>
                                </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                {% endif %}

                {% if my_games_inactives is empty %}

                {% else %}
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
                    {% for game in my_games_inactives %}
                    <tr>
                        <td class="date">
                            <strong>{{ language.translate("Euromillions") }}</strong>
                            {{ game.startDrawDate }}
                        </td>
                        <td class="numbers">
                            <div class="myCol">
                                {#
                                {{ game.regular_numbers }} <span class="star">{{ game.lucky_numbers }}</span>
                                #}
                                <span class="num">1</span>
                                <span class="num">2</span>
                                <span class="num">30</span>
                                <span class="num">37</span>
                                <span class="num">49</span>
                                <span class="num star">7</span>
                                <span class="num star">11</span>
                            </div>
                        </td>
                        <td class="action">
                            <a href="javascript:void(0);" class="btn blue">{{ language.translate("Play it <span class='desktop'>again</span> for") }} 2,35 &euro;</a>
                        </td>
                    </tr>
                    {% endfor %}
                    <tr class="special">
                        <td class="date">
                            <strong>{{ language.translate("Euromillions") }}</strong>
                            12 May 2015
                        </td>
                        <td class="numbers">
                            <div class="myCol">
                                <span class="num">1</span>
                                <span class="num">2</span>
                                <span class="num">30</span>
                                <span class="num">37</span>
                                <span class="num">49</span>
                                <span class="num star">7</span>
                                <span class="num star">11</span>
                            </div>
                        </td>
                        <td class="action">
                            <a href="javascript:void(0);" class="btn blue">{{ language.translate("Play it <span class='desktop'>again</span> for") }} 2,35 &euro;</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                    {% include "account/_paging.volt" %}
                {% endif %}
            </div>
        </div>
    </main>
{% endblock %}