<div class="tickets-table">
    <table>

        <tr>
            <td class="lottery"></td>
            <td class="date-from">Draw Date</td>
            <td class="date-to"></td>
            <td class="numbers">Numbers Played</td>
        </tr>

        {% for my_christmas_active in my_christmas_actives %}
        <tr>
            <td class="lottery">
                Christmas
            </td>
            <td class="date-from">
                <?php
                    $christmasDateActive = new DateTime($my_christmas_active['start_draw_date']);
                ?>
                {{ christmasDateActive.format(language.translate('dateformat')) }}
            </td>
            <td class="date-to">

            </td>
            <td class="numbers">
                <div class="numbers--row">
                    <span>{{ my_christmas_active['line_regular_number_one'] }}</span>
                    <span>{{ my_christmas_active['line_regular_number_two'] }}</span>
                    <span>{{ my_christmas_active['line_regular_number_three'] }}</span>
                    <span>{{ my_christmas_active['line_regular_number_four'] }}</span>
                    <span>{{ my_christmas_active['line_regular_number_five'] }}</span>
                    {#<span class="star">{{ my_christmas_active['line_lucky_number_one'] }}</span>#}
                    {#<span class="star">{{ my_christmas_active['line_lucky_number_two'] }}</span>#}
                </div>
            </td>
        </tr>
        {% endfor %}
    </table>
</div>
