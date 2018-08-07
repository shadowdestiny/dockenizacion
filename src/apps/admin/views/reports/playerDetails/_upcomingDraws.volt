<h3 class="h4">Upcoming draws</h3>
{% if my_games_actives is empty %}
    This player didn't purchase tickets for upcoming draws.
{% else %}
    <table class="table-program" width="100%">
        <thead>
        <tr class="special">
            <th width="150px">
                Draw date
            </th>
            <th width="150px">
                Lottery
            </th>
            <th>
                Numbers played
            </th>
        </tr>
        </thead>
        <tbody>
        {% for index,upcoming in my_games_actives %}
            {#{{ dump(my_games_actives) }}#}
            <tr>
                <td align="center">
                    {{ nextDrawDate }}
                </td>
                <?php $rows = count($my_games_actives[$index]); ?>
                <?php
                            $numColumn = 0;
                            for($i=0;$i<$rows/2;$i++){
                                $game = $my_games_actives[$index][$numColumn];
                                $game = $game->get(0);
                                $lottery = $game->lotteryName;
                ?>
                <td align="center">
                    <strong>{{ lottery }}</strong>
                </td>
                <td>
                    <table>

                        <tr>
                            <td class="numbers">
                                <div>
                                    <?php $regular = explode(',', $game->lines['bets']['regular']);?>
                                    <?php $lucky = explode(',', $game->lines['bets']['lucky']);?>

                                    {% for regular_number in regular  %}
                                        <span class="num">{{ regular_number }}</span>
                                    {% endfor %}
                                    {% if lottery === 'PowerBall' %}
                                        <span class="num yellow">{{ lucky[1] }}</span>
                                    {% else %}
                                        {% for lucky_number in lucky  %}
                                                <span class="num yellow">{{ lucky_number }}</span>
                                        {% endfor %}
                                    {% endif %}
                                </div>
                            </td>
                            <?php if(count($my_games_actives[$index]) > 1 && $numColumn < $rows-1 ) {?>
                            <td class="numbers">
                                <?php
                                    if(isset($my_games_actives[$index][$numColumn+1])) {
                                        $game=$my_games_actives[$index][$numColumn+1];
                                    } else {
                                        $game=$my_games_actives[$index][$numColumn];
                                    }
                                    $game = $game->get(0);
                                ?>
                                <div>
                                    <?php $regular = explode(',', $game->lines['bets']['regular']);?>
                                    <?php $lucky = explode(',', $game->lines['bets']['lucky']);?>
                                    {% for regular_number in regular  %}
                                        <span class="num">{{ regular_number }}</span>
                                    {% endfor %}
                                    {% for lucky_number in lucky  %}
                                        <span class="num yellow">{{ lucky_number }}</span>
                                    {% endfor %}
                                </div>
                            </td>
                            <?php $numColumn=$numColumn+2;?>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    {{ paginator_view_actives }}
{% endif %}