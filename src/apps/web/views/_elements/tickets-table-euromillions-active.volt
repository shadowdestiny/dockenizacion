<div class="tickets-table">
    <table>

        <tr>
            <td class="lottery"></td>
            <td class="date-from">{{ language.translate("tickets_past_date") }}</td>
            <td class="date-to"></td>
            <td class="numbers">{{ language.translate("tickets_SubsPast_numbers") }}</td>
        </tr>

        {% for date,play_configs in my_games_actives.result %}
            <tr>
                <td class="lottery">
                    {{ language.translate("Euromillions") }}
                </td>
                <td class="date-from">
                    <?php
                    $euromillionsDateActive = new DateTime($date);
                ?>
                    {{ euromillionsDateActive.format(language.translate('dateformat')) }}
                </td>
                <td class="date-to">

                </td>
                <td class="numbers">
                    {% for play_config in play_configs %}
                        <?php $play_config = $play_config->get(0); ?>
                        <?php $regular = explode(',', $play_config->lines['bets']['regular']);?>
                        <?php $lucky = explode(',', $play_config->lines['bets']['lucky']);?>
                        <div class="numbers--row">
                            {% for regular_number in regular %}
                                <span>{{ regular_number }}</span>
                            {% endfor %}
                            {% for lucky_number in lucky %}
                                <span class="star">{{ lucky_number }}</span>
                            {% endfor %}
                        </div>
                    {% endfor %}
                </td>
            </tr>
        {% endfor %}
    </table>
</div>
