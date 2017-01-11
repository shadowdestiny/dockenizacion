{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
    <link rel="stylesheet" href="/a/css/pagination.css">
{% endblock %}
{% block bodyClass %}games{% endblock %}

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
                {% set activeSubnav='{"myClass": "games"}'|json_decode %}
                {% include "account/_nav.volt" %}
            </div>
            <div class="box-basic content">
                <h1 class="h1 title">{{ language.translate("Tickets") }}</h1>

                {% if my_games_actives is empty %}
                    <div class="box info">
                        <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                        <span class="txt">
                            {{ language.translate("You have purchased no tickets for upcoming draws.") }} <a href="/{{ lottery }}/play">{{ language.translate("Play now and start to win.") }}</a>
                        </span>
                    </div>

                    <a href="/{{ lottery }}/play" class="no-data img">
                        <div class="txt">
                            <span class="h1">
                                {{ language.translate("Dream to Win<br>
                                a real treasure awaits!") }}
                            </span>

                            <span class="jackpot-txt">{{ language.translate("Jackpot this week") }}</span>

                            {% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
                            {% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
                            <span class="btn blue big" >{{ language.translate("PLAY NOW") }}</span>
                        </div>
                    </a>
                {% endif %}

                {% if my_games_actives is empty %}

                {% else %}
                    <h2 class="h3">{{ language.translate("My Tickets") }}</h2>
                    <table class="present cl table ui-responsive" data-role="table" data-mode="reflow">
                        <thead>
                            <th class="date">
                                {{ language.translate("Draw date") }}
                            </th>
                            <th class="date">
                                {{ language.translate("Lottery") }}
                            </th>

                            {#<th class="when">#}
                                {#{{ language.app("Duration") }}#}
                            {#</th>#}
                            <th class="numbers">
                                {{ language.translate("Numbers <span class='desktop'>played</span>") }}
                            </th>
                            {#<th class="action">#}
                                {#{{ language.app("Actions") }}#}
                            {#</th>#}
                        </thead>
                        <tbody>
                            {% for index,upcoming in my_games_actives.result %}
                                <tr>
                                    <td class="date">
                                        <div class="myCol">
                                            {{ nextDrawDate }}
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ language.translate("Euromillions") }}</strong>
                                    </td>
                                    <td>
                                        <table border="1">
                                            <?php $rows = count($my_games_actives->result[$index]); ?>
                                            <?php
                                            $numColumn = 0;
                                            for($i=0;$i<$rows/2;$i++){
                                            ?>
                                            <?php
                                                $game = $my_games_actives->result[$index][$numColumn];
                                                $game = $game->get(0);
                                            ?>
                                            <tr>
                                                <td class="numbers">
                                                    <div class="myCol">
                                                        <?php $regular = explode(',', $game->lines['bets']['regular']);?>
                                                        <?php $lucky = explode(',', $game->lines['bets']['lucky']);?>

                                                        {% for regular_number in regular  %}
                                                            <span class="num">{{ regular_number }}</span>
                                                        {% endfor %}
                                                        {% for lucky_number in lucky  %}
                                                            <span class="num star">{{ lucky_number }}</span>
                                                        {% endfor %}
                                                    </div>
                                                </td>
                                                <?php if(count($my_games_actives->result[$index]) > 1 && $numColumn < $rows-1 ) {?>
                                                <td class="numbers">
                                                    <?php
                                                    if(isset($my_games_actives->result[$index][$numColumn+1])) {
                                                        $game=$my_games_actives->result[$index][$numColumn+1];
                                                    } else {
                                                        $game=$my_games_actives->result[$index][$numColumn];
                                                    }
                                                    $game = $game->get(0);
                                                    ?>
                                                    <div class="myCol">
                                                        <?php $regular = explode(',', $game->lines['bets']['regular']);?>
                                                        <?php $lucky = explode(',', $game->lines['bets']['lucky']);?>
                                                        {% for regular_number in regular  %}
                                                            <span class="num">{{ regular_number }}</span>
                                                        {% endfor %}
                                                        {% for lucky_number in lucky  %}
                                                            <span class="num star">{{ lucky_number }}</span>
                                                        {% endfor %}
                                                    </div>
                                                </td>
                                                <?php $numColumn=$numColumn+2;?>
                                                <?php } ?>
                                            </tr>
                                            <?php } ?>
                                        </table>
                                    </td>
                                    {#<td class="action">
                                        <a href="javascript:void(0);" class="btn blue">Edit <svg class="ico v-pencil"><use xlink:href="/w/svg/icon.svg#v-pencil"></use></svg></a>
                                        <a href="javascript:void(0);" class="btn red">Delete <svg class="ico v-cross"><use xlink:href="/w/svg/icon.svg#v-cross"></use></svg></a>
                                    </td>#}
                                </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                {% endif %}

                {% if my_games_inactives is empty %}

                {% else %}
                    <h2 class="h3">{{ language.translate("Past Games") }}</h2>
                <table id="game-history" class="cl table ui-responsive" data-role="table" data-mode="reflow">
                    <thead>
                    <tr>
                        <th class="date">
                            {{ language.translate("Draw date") }}
                        </th>
                        <th class="date">
                            {{ language.translate("Lottery") }}
                        </th>

                        {#<th class="when">#}
                        {#{{ language.app("Duration") }}#}
                        {#</th>#}
                        <th class="numbers">
                            {{ language.translate("Numbers <span class='desktop'>played</span>") }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    {% for index,game in my_games_inactives %}
                        <tr>
                            <td class="date">
                                <div class="myCol">
                                    <?php $date = new \DateTime($index);
                                          $startDrawDate = $date->format('Y M j');?>
                                    {{ startDrawDate }}
                                </div>
                            </td>
                            <td>
                                <strong>{{ language.translate("Euromillions") }}</strong>
                            </td>
                            <td class="numbers">
                                <table border="1">
                                    <?php $rows = count($game);?>
                                    <?php
                                        $numColumn = 0;
                                        for($i=0;$i<$rows/2;$i++){
                                        ?>
                                    <?php
                                        $pastGame = $game[$numColumn];
                                    ?>
                                    <tr>
                                        <td class="numbers">
                                            <div class="myCol">
                                                {% for r,regular_number in pastGame.numbers %}
                                                    <span class="num <?php if($regular_number > 1) echo 'highlight' ?>">{{ r }}</span>
                                                {% endfor %}
                                                {% for s,lucky_number in pastGame.stars  %}
                                                    <span class="num star <?php if($lucky_number > 1) echo 'highlight' ?>">{{ s }}</span>
                                                {% endfor %}
                                                <?php if($pastGame->prize > 0){?>
                                                    <span class="">({{ pastGame.prize }}€)</span>
                                                <?php } ?>

                                            </div>
                                        </td>
                                        <?php if(count($game) > 1 && $numColumn < $rows-1 ) {?>
                                        <td class="numbers">
                                            <?php
                                            if(isset($game[$numColumn+1])) {
                                            $pastGame=$game[$numColumn+1];
                                            } else {
                                            $pastGame=$game[$numColumn];
                                            }
                                            ?>
                                            <div class="myCol">
                                                {% for r,regular_number in pastGame.numbers  %}
                                                    <span class="num <?php if($regular_number > 1) echo 'highlight' ?> ">{{ r }}</span>
                                                {% endfor %}
                                                {% for s,lucky_number in pastGame.stars  %}
                                                    <span class="num star <?php if($lucky_number > 1) echo 'highlight' ?>">{{ s }}</span>
                                                {% endfor %}
                                                <?php if($pastGame->prize > 0){?>
                                                    <span class="">({{ pastGame.prize }}€)</span>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <?php } ?>
                                        <?php $numColumn=$numColumn+2;?>
                                    </tr>
                                    <?php } ?>
                                </table>
                            </td>
                        </tr>
                    {% endfor %}
                    </tbody>
                </table>
                    {{ paginator_view }}
                {% endif %}
            </div>
        </div>
    </main>
{% endblock %}
