<div class="tickets-table">
    <table>

        <tr>
            <td class="lottery">{{ language.translate("tickets_past_lotto") }}</td>
            <td class="date-from">{{ language.translate("tickets_past_date") }}</td>
            <td class="date-to">{{ language.translate("tickets_play_again") }}</td>
            <td class="numbers">{{ language.translate("tickets_SubsPast_numbers") }}</td>
        </tr>
        {% for name,results in my_games_inactives %}
            {% for date,play_configs in results %}
                <tr>
                    <td class="lottery">
                        {{ name }}
                    </td>
                    <td class="date-from">
                        <?php
                        $euromillionsDateInactive = new DateTime($date);
                    ?>
                        {{ euromillionsDateInactive.format(language.translate('dateformat')) }}
                    </td>
                    <td class="date-to">
                        {% set tickets_inactives = '' %}
                        {% for bet in play_configs %}
                            {% set numbers_inactives = '' %}
                            {% set stars_inactives = '' %}
                            {% for bet_number,value in bet.regular_numbers %}
                                {% if loop.last %}
                                    {% set numbers_inactives = numbers_inactives ~ value ~ '.' %}
                                {% else %}
                                    {% set numbers_inactives = numbers_inactives ~ value ~ ',' %}
                                {% endif %}
                            {% endfor %}
                            {% for bet_star,value in bet.lucky_numbers %}
                                {% if loop.last %}
                                    {% set stars_inactives = stars_inactives ~ value %}
                                {% else %}
                                    {% set stars_inactives = stars_inactives ~ value ~ ',' %}
                                {% endif %}
                            {% endfor %}
                            {% if loop.last %}
                                {% set tickets_inactives = tickets_inactives ~ numbers_inactives ~ stars_inactives %}
                            {% else %}
                                {% set tickets_inactives = tickets_inactives ~ numbers_inactives ~ stars_inactives ~ '|' %}
                            {% endif %}
                        {% endfor %}
                        <a onClick="euromillionsPlay('{{ tickets_inactives }}', '{{ play_configs[0].lotteryName }}');" href="#">
                            {{ language.translate("tickets_play_again") }}
                        </a>
                    </td>
                    <td class="numbers">
                        {% for play_config in play_configs %}
                            <?php $lucky = null;?>
                            {% for lucky_number,badArray in play_config.lucky_numbers %}
                                <?php $lucky[] = $badArray;?>
                            {% endfor %}
                            <div class="numbers--row">
                                {% for regular_number,badArray in play_config.regular_numbers %}
                                    <span>{{ badArray }}</span>
                                {% endfor %}
                                {% if (play_config.lotteryName == 'MegaSena')%}
                                <span>{{ lucky[1] }}</span>
                                {% elseif (play_config.lotteryName == 'PowerBall') or  (play_config.lotteryName == 'MegaMillions')%}
                                    <span class="star">{{ lucky[1] }}</span>
                                {% else %}
                                    {% for lucky_number,badArray in play_config.lucky_numbers %}
                                        <span class="star">{{ badArray }}</span>
                                    {% endfor %}
                                {% endif %}
                            </div>
                        {% endfor %}
                    </td>
                </tr>
            {% endfor %}
        {% endfor %}
    </table>
    {{ paginator_view }}
</div>
