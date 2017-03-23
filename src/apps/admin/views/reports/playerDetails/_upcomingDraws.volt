<h3 class="h4">Upcoming Draws</h3>
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
            <tr>
                <td align="center">
                    {{ nextDrawDate }}
                </td>
                <td align="center">
                    <strong>Euromillions</strong>
                </td>
                <td>
                    <table>
                        <tr>
                        {% for index,upcoming in my_games_actives %}
                                <td class="numbers">
                                    <div>
                                        <span class="num">{{ my_games_actives[index]['line_regular_number_one'] }}</span>
                                        <span class="num">{{ my_games_actives[index]['line_regular_number_two'] }}</span>
                                        <span class="num">{{ my_games_actives[index]['line_regular_number_three'] }}</span>
                                        <span class="num">{{ my_games_actives[index]['line_regular_number_four'] }}</span>
                                        <span class="num">{{ my_games_actives[index]['line_regular_number_five'] }}</span>
                                        <span class="num yellow">{{ my_games_actives[index]['line_lucky_number_one'] }}</span>
                                        <span class="num yellow">{{ my_games_actives[index]['line_lucky_number_two'] }}</span>
                                    </div>
                                </td>
                                {% if (index+1) % 2 == 0 %}</tr><tr>{% endif %}
                        {% endfor %}
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    {#{{ paginator_view_actives }}#}
{% endif %}