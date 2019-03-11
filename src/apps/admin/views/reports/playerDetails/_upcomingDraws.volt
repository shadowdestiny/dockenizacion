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
        {% for lottery, my_game_active in my_games_actives %}
            {% for date, my_games in my_game_active %}
                {% for index,upcoming in my_games %}
                    <div>
                        <tr>
                            <td align="center">
                                <strong>{{ date }}</strong>
                            </td>
                            <td align="center">
                                {{ lottery }}
                            </td>
                            <td class="numbers">
                                {% for regular_number in  upcoming.regular_numbers %}
                                    <span class="num">{{ regular_number }}</span>
                                {% endfor %}

                                {% if lottery === 'PowerBall' or lottery === 'MegaMillions'%}
                                    <span class="num yellow">{{ upcoming.lucky_numbers[1] }}</span>
                                {% else %}
                                    {% for lucky_number in upcoming.lucky_numbers  %}
                                        <span class="num yellow">{{ lucky_number }}</span>
                                    {% endfor %}
                                {% endif %}
                            </td>
                        </tr>
                    </div>

                {% endfor %}
            {% endfor %}
        {% endfor %}
        </tbody>
    </table>
    {{ paginator_view_actives }}
{% endif %}