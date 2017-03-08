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
        {% for index,game in my_games_inactives %}
            <tr>
                <td class="date" align="center">
                        <?php
                            $date = new \DateTime($index);
                            $startDrawDate = $date->format('Y M j');
                        ?>
                        {{ startDrawDate }}
                </td>
                <td align="center">
                    <strong>Euromillions</strong>
                </td>
                <td class="numbers">
                    <table>
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
                                        <span class="num yellow <?php if($lucky_number > 1) echo 'highlight' ?>">{{ s }}</span>
                                    {% endfor %}
                                    <?php if($pastGame->prize > 0){?>
                                    <span class="">({{ pastGame.prize }})</span>
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
                                        <span class="num yellow <?php if($lucky_number > 1) echo 'highlight' ?>">{{ s }}</span>
                                    {% endfor %}
                                    <?php if($pastGame->prize > 0){?>
                                    <span class="">({{ pastGame.prize }})</span>
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
    {{ paginator_view_inactives }}
{% endif %}