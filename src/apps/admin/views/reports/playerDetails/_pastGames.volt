<h3 class="h4">Past Games</h3>
{% if my_games_inactives is empty %}
    This player didn't purchase tickets for past draws.
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
                Numbers <span class='desktop'>played</span>
            </th>
        </tr>
        </thead>
        <tbody>
        {% for lottery, my_game_inactive in my_games_inactives %}
              {% for date, my_games in my_game_inactive %}
                      {% for index,past in my_games %}
                            <div>
                                <tr>
                                    <td align="center">
                                            <strong>{{ date }}</strong>
                                    </td>
                                    <td align="center">
                                          {{ lottery }}
                                    </td>
                                    <td class="numbers">
                                        {% for regular_number in  past.regular_numbers %}
                                            <span class="num">{{ regular_number }}</span>
                                        {% endfor %}

                                        {% if lottery === 'PowerBall' or lottery === 'MegaMillions'%}
                                               <span class="num yellow">{{ upcoming.lucky_numbers[1] }}</span>
                                        {% else %}
                                            {% for lucky_number in past.lucky_numbers  %}
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
    {{ paginator_view_inactives }}
{% endif %}