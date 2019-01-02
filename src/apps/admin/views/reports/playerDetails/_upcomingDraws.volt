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
        <div>
            <?php $rows = count($my_games_actives[$index]); ?>
            <?php
                $numColumn = 0;
                for($i=0;$i<$rows;$i++){
                    $game = $my_games_actives[$index][$numColumn];
                    $game = $game->get(0);
                    $lottery = $game->lotteryName;
            ?>
            <tr>
                <td align="center">
                    {{ nextDrawDate }}
                </td>
                <td align="center">
                    <strong>{{ lottery }}</strong>
                </td>
                <td class="numbers">
                    <?php $regular = explode(',', $game->lines['bets']['regular']);?>
                    <?php $lucky = explode(',', $game->lines['bets']['lucky']);?>

                    {% for regular_number in regular  %}
                        <span class="num">{{ regular_number }}</span>
                    {% endfor %}
                    {% if lottery === 'PowerBall' or lottery === 'MegaMillions'%}
                        <span class="num yellow">{{ lucky[1] }}</span>
                    {% else %}
                        {% for lucky_number in lucky  %}
                                <span class="num yellow">{{ lucky_number }}</span>
                        {% endfor %}
                    {% endif %}
                    <?php $numColumn=$numColumn+1;?>
                    <?php } ?>
                </td>
            </tr>
        </div>

        {% endfor %}
        </tbody>
    </table>
    {{ paginator_view_actives }}
{% endif %}