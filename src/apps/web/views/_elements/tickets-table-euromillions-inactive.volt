<div class="tickets-table">
    <table>
        {% for date,play_configs in my_games_inactives %}
        <tr>
            <td class="lottery">
                {{ language.translate("Euromillions") }}
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
                {% for play_config in play_configs %}
                    <div class="numbers--row">
                    {% for regular_number,badArray in play_config.numbers  %}
                        <span>{{ regular_number }}</span>
                    {% endfor %}
                    {% for lucky_number,badArray in play_config.stars  %}
                        <span class="star">{{ lucky_number }}</span>
                    {% endfor %}
                    </div>
                {% endfor %}
            </td>
        </tr>
        {% endfor %}
    </table>
    {{ paginator_view }}
</div>
