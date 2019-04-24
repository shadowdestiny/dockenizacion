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
        {% for index,lotteries in my_games_inactives %}
        <?php $band=false; ?>;
        {% for lottery,game in lotteries %}

            <tr>
                <td class="date" align="center">
                    <?php
                            if(!$band)
                            {
                                $band=true;
                                $date = new \DateTime($index);
                                $startDrawDate = $date->format('Y M j');
                                echo($startDrawDate);
                            }
                            ?>
                </td>
                <?php $rowslottery = count($game);?>
                <?php
                            $numColumnlottery = 0;
                            for($i=0;$i<$rowslottery/2;$i++){
                            ?>
                <?php
                            $pastGamelottery = $game[$numColumnlottery];
                            $lottery2 = $pastGamelottery->lotteryName;
                }?>
                <td align="center">
                    <strong>{{ lottery2 }}</strong>
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
                            <td class="numbers" style="border-right: 1px solid purple;">
                                <div class="myCol">
                                    {% for r,regular_number in pastGame.numbers %}
                                        <span class="num <?php if($regular_number > 1) echo 'highlight' ?>">{{ r }}</span>
                                    {% endfor %}
                                    {% for s,lucky_number in pastGame.stars  %}
                                        {% if s != 0 %}
                                            <span class="num yellow <?php if($lucky_number > 1) echo 'highlight' ?>">{{ s }}</span>
                                        {% endif %}
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
                                        {% if s != 0 %}
                                            <span class="num yellow <?php if($lucky_number > 1) echo 'highlight' ?>">{{ s }}</span>
                                        {% endif %}

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
        {% endfor %}
        </tbody>
    </table>
    {{ paginator_view_inactives }}
{% endif %}