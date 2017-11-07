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
{% block template_scripts_code %}
    function euromillionsPlay(numbers)
    {
        var storageNumbers = '[', playConfigs = numbers.split("|");

        playConfigs.forEach(function(element) {
            playConfig = element.split(".");
            storageNumbers += '{"numbers":['+playConfig[0]+'],"stars":['+playConfig[1]+']},';
        });

        storageNumbers = storageNumbers.slice(0, -1) + ']';

        localStorage.setItem('bet_line', storageNumbers);

        window.location.href = '/{{ language.translate('link_euromillions_play') }}';
    }
{% endblock %}
{% block body %}
    <main id="content" class="account-page tickets-page">
        <div class="wrapper">
            <div class="nav">
                {% set activeSubnav='{"myClass": "games"}'|json_decode %}
                {% include "account/_nav.volt" %}
            </div>
            <div class="content">






                {#Clear html start#}
                <form action="" class="tickets-form">

                    <div class="mobile-filter-block">
                        {% include "_elements/tickets-filter.volt" %}
                    </div>

                    <h3>
                        My Subscriptions
                    </h3>

                    {% include "_elements/tickets-filter.volt" %}
                    {% include "_elements/tickets-table.volt" %}

                    <h3>
                        My Tickets
                    </h3>

                    {% include "_elements/tickets-filter.volt" %}
                    {% include "_elements/tickets-table.volt" %}

                    <h3>
                        Past Subscriptions
                    </h3>

                    {% include "_elements/tickets-filter.volt" %}
                    {% include "_elements/tickets-table.volt" %}

                </form>
                {#Clear html end#}






















                <h1 class="h1 title">{{ language.translate("tickets_head") }}</h1>

                {% if my_games_actives is empty and my_subscription_actives is empty %}
                    <div class="box info">
                        <svg class="ico v-info"><use xlink:href="/w/svg/icon.svg#v-info"></use></svg>
                        <span class="txt">
                            <a href="/{{ language.translate("link_euromillions_play") }}">{{ language.translate("tickets_notickets") }}</a>
                        </span>
                    </div>

                    <a href="/{{ language.translate("link_euromillions_play") }}" class="no-data img">
                        <div class="txt">
                            <span class="h1">
                                {{ language.translate("Dream to Win<br>
                                a real treasure awaits!") }}
                            </span>

                            <span class="jackpot-txt">{{ language.translate("nextDraw_Estimate") }}</span>

                            {% set extraClass='{"boxvalueClass": "","currencyClass":"yellow","valueClass":"yellow"}'|json_decode %}
                            {% include "_elements/jackpot-value" with ['extraClass': extraClass] %}
                            <span class="btn blue big" >{{ language.translate("nextDraw_btn") }}</span>
                        </div>
                    </a>
                {% endif %}

                {% if my_subscription_actives is not empty %}
                    <h2 class="h3">{{ language.translate("tickets_SubsUpcoming") }}</h2>
                    <table class="present cl table ui-responsive" data-role="table" data-mode="reflow">
                        <thead>
                        <th width="215px">
                            {{ language.translate("tickets_SubsUpcoming_date") }}
                        </th>
                        <th class="date">
                            {{ language.translate("tickets_SubsUpcoming_lotto") }}
                        </th>
                        <th class="numbers">
                            {{ language.translate("tickets_SubsUpcoming_numbers") }}
                        </th>
                        </thead>
                        <tbody>
                        {% for my_subscription_active in my_subscription_actives %}
                            <tr>
                                <td align="center">
                                    {{ my_subscription_active['start_draw_date'] }} to {{ my_subscription_active['last_draw_date'] }}
                                </td>
                                <td align="center">
                                    <strong>Euromillions</strong>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                        {% for i in 1..my_subscription_active['lines'] %}
                                            <td class="numbers" style="border-bottom: 0px;">
                                                <span class="num">{{ my_subscription_active[i-1]['line_regular_number_one'] }}</span>
                                                <span class="num">{{ my_subscription_active[i-1]['line_regular_number_two'] }}</span>
                                                <span class="num">{{ my_subscription_active[i-1]['line_regular_number_three'] }}</span>
                                                <span class="num">{{ my_subscription_active[i-1]['line_regular_number_four'] }}</span>
                                                <span class="num">{{ my_subscription_active[i-1]['line_regular_number_five'] }}</span>
                                                <span class="num yellow">{{ my_subscription_active[i-1]['line_lucky_number_one'] }}</span>
                                                <span class="num yellow">{{ my_subscription_active[i-1]['line_lucky_number_two'] }}</span>
                                            </td>
                                            {% if i % 2 == 0 %}</tr><tr>{% endif %}
                                        {% endfor %}
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                {% endif %}

                {% if my_christmas_actives is not empty %}
                    <h2 class="h3">{{ language.translate("My Christmas Tickets") }}</h2>
                    <table class="present cl table ui-responsive" data-role="table" data-mode="reflow">
                        <thead>
                        <th width="215px">
                            {{ language.translate("Draw date") }}
                        </th>
                        <th class="date">
                            {{ language.translate("Lottery") }}
                        </th>
                        <th class="numbers">
                            {{ language.translate("Number <span class='desktop'>played</span>") }}
                        </th>
                        </thead>
                        <tbody>
                        {% for my_christmas_active in my_christmas_actives %}
                            <tr>
                                <td align="center">
                                    {{ my_christmas_active['start_draw_date'] }}
                                </td>
                                <td align="center">
                                    <strong>Christmas</strong>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                            <td class="numbers" style="border-bottom: 0px;">
                                                <span class="num">{{ my_christmas_active['line_regular_number_one'] }}</span>
                                                <span class="num">{{ my_christmas_active['line_regular_number_two'] }}</span>
                                                <span class="num">{{ my_christmas_active['line_regular_number_three'] }}</span>
                                                <span class="num">{{ my_christmas_active['line_regular_number_four'] }}</span>
                                                <span class="num">{{ my_christmas_active['line_regular_number_five'] }}</span>
                                                <!-- span class="num yellow">{{ my_christmas_active['line_lucky_number_one'] }}</span>
                                                <span class="num yellow">{{ my_christmas_active['line_lucky_number_two'] }}</span -->
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                {% endif %}

                {% if my_games_actives is empty %}

                {% else %}
                    <h2 class="h3">{{ language.translate("tickets_upcoming") }}</h2>
                    <table class="present cl table ui-responsive" data-role="table" data-mode="reflow">
                        <thead>
                            <th class="date">
                                {{ language.translate("tickets_upcoming_date") }}
                            </th>
                            <th class="date">
                                {{ language.translate("tickets_upcoming_lotto") }}
                            </th>

                            {#<th class="when">#}
                                {#{{ language.app("Duration") }}#}
                            {#</th>#}
                            <th class="numbers">
                                {{ language.translate("tickets_upcoming_numbers") }}
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
                                            {{ my_games_actives.result[index][0].startDrawDate }}
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

                {% if my_subscription_inactives is not empty %}
                    <h2 class="h3">{{ language.translate("tickets_SubsPast") }}</h2>
                    <table id="game-history" class="cl table ui-responsive" data-role="table" data-mode="reflow">
                        <thead>
                        <tr>
                            <th width="215px">
                                {{ language.translate("tickets_SubsPast_date") }}
                            </th>
                            <th class="date">
                                {{ language.translate("tickets_SubsPast_lotto") }}
                            </th>

                            {#<th class="when">#}
                            {#{{ language.app("Duration") }}#}
                            {#</th>#}
                            <th class="numbers">
                                {{ language.translate("tickets_SubsPast_numbers") }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for my_subscription_inactive in my_subscription_inactives %}
                            <tr>
                                <td align="center">
                                    {{ my_subscription_inactive['start_draw_date'] }} to {{ my_subscription_inactive['last_draw_date'] }}
                                </td>
                                <td align="center">
                                    <strong>Euromillions</strong>
                                </td>
                                <td>
                                    <table border="0">
                                        <tr>
                                            {% for i in 1..my_subscription_inactive['lines']  %}
                                            <td class="numbers" style="border-bottom: 0px;">
                                                <span class="num">{{ my_subscription_inactive[i-1]['line_regular_number_one'] }}</span>
                                                <span class="num">{{ my_subscription_inactive[i-1]['line_regular_number_two'] }}</span>
                                                <span class="num">{{ my_subscription_inactive[i-1]['line_regular_number_three'] }}</span>
                                                <span class="num">{{ my_subscription_inactive[i-1]['line_regular_number_four'] }}</span>
                                                <span class="num">{{ my_subscription_inactive[i-1]['line_regular_number_five'] }}</span>
                                                <span class="num yellow">{{ my_subscription_inactive[i-1]['line_lucky_number_one'] }}</span>
                                                <span class="num yellow">{{ my_subscription_inactive[i-1]['line_lucky_number_two'] }}</span>
                                            </td>
                                            {% if i % 2 == 0 %}</tr><tr>{% endif %}
                                            {% endfor %}
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    {{ paginator_view_subs_inactives }}
                {% endif %}

                {% if my_games_inactives is empty %}

                {% else %}
                    <h2 class="h3">{{ language.translate("tickets_past") }}</h2>
                <table id="game-history" class="cl table ui-responsive" data-role="table" data-mode="reflow">
                    <thead>
                    <tr>
                        <th class="date">
                            {{ language.translate("tickets_past_date") }}
                        </th>
                        <th class="date">
                            {{ language.translate("tickets_past_lotto") }}
                        </th>
                        <th>

                        </th>
                        {#<th class="when">#}
                        {#{{ language.app("Duration") }}#}
                        {#</th>#}
                        <th class="numbers">
                            {{ language.translate("tickets_past_numbers") }}
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
                            <td>
                                {% set tickets_inactives = '' %}
                                {% for bet in game %}
                                    {% set numbers_inactives = '' %}
                                    {% set stars_inactives = '' %}
                                    {% for bet_number,value in bet.numbers %}
                                        {% if loop.last %}
                                            {% set numbers_inactives = numbers_inactives ~ bet_number ~ '.' %}
                                        {% else %}
                                            {% set numbers_inactives = numbers_inactives ~ bet_number ~ ',' %}
                                        {% endif %}
                                    {% endfor %}
                                    {% for bet_star,value in bet.stars %}
                                        {% if loop.last %}
                                            {% set stars_inactives = stars_inactives ~ bet_star %}
                                        {% else %}
                                            {% set stars_inactives = stars_inactives ~ bet_star ~ ',' %}
                                        {% endif %}
                                    {% endfor %}
                                    {% if loop.last %}
                                        {% set tickets_inactives = tickets_inactives ~ numbers_inactives ~ stars_inactives %}
                                    {% else %}
                                        {% set tickets_inactives = tickets_inactives ~ numbers_inactives ~ stars_inactives ~ '|' %}
                                    {% endif %}
                                {% endfor %}
                                <a onClick="euromillionsPlay('{{ tickets_inactives }}');" href="#">
                                    {{ language.translate("tickets_play_again") }}
                                </a>
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
