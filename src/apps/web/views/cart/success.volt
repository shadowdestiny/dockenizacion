{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/cart.css">{% endblock %}
{% block template_scripts %}{% endblock %}
{% block bodyClass %}
cart success minimal
{% include "_elements/jlength.volt" %}
{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "success"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script><script>
(function(window) {
  if (window.location !== window.top.location) {
    window.top.location = window.location;
  }
})(this);
{% if (lottery_name == 'EuroMillions') %}
localStorage.removeItem('bet_line');
{%endif%}
{% if (lottery_name == 'PowerBall') %}
    localStorage.removeItem('pb_bat_line');
{%endif%}
{% if (lottery_name == 'MegaMillions') %}
    localStorage.removeItem('mm_bet_line');
{%endif%}
{% if (lottery_name == 'EuroJackpot') %}
    localStorage.removeItem('ej_bet_line');
{%endif%}
{% if (lottery_name == 'MegaSena') %}
    localStorage.removeItem('ms_bet_line');
{%endif%}

{% if (lottery_name == 'MegaSena') %}
    // I'm not proud of this script but it resolves, I would have preferred to modify the core to avoid these tricks, just as I was doing in the revision: c42364e701cecbaed91c998f31aa8d8b3bfeaed9 and f8899d71fcd2a47f43b95afe5f74345460d1da46
    var megasena_elements = $("ul.numbers");
    megasena_elements.each(function(i,elem){
        var li_order = [];
        $(this).find(".circle_megasena").each(function(i){
            li_order.push(parseInt($(this).text()));
        });
        var li = '';
        li_order = li_order.sort(function(a,b){
            return a - b;
        });
        $.each(li_order,function(i,val){
            li+= '<li class="circle_megasena">'+((val < 10) ? ("0" + val): val)+'</li>'
        });
        $(this).html(li);
    });
{% endif %}

</script>
{% endblock %}
{% block template_scripts_code %}
    $(function(){
        var html_formatted_offset = [];
        $('.countdown-black .dots').eq(2).hide();
        $('.countdown-black .seconds').hide();
        var element = $('.countdown-black');
        var html_formatted = element.html();
        $('.countdown-black .dots').eq(2).show();
        $('.countdown-black .seconds').show();
        $('.countdown-black .day').remove();
        $('.countdown-black .dots').eq(0).remove();
        html_formatted_offset[0] = $('.countdown-black').html();
        $('.countdown-black .hour').remove();
        $('.countdown-black .dots').eq(0).remove();
        html_formatted_offset[1] = $('.countdown-black').html();
        $('.countdown-black .minute').remove();
        $('.countdown-black .dots').eq(0).remove();
        html_formatted_offset[2] = $('.countdown-black').html();
        var finish_action = function(){
        $('.box-next-draw .btn.red').remove();


    }
    var date = '{{ date_draw }}'; {# To test "2015/11/17 10:49:00"  #}
    var finish_text = "<div class='closed'>{{ language.translate('The Draw is closed') }}</div>";
    count_down(element,html_formatted,html_formatted_offset, date,finish_text, finish_action);
    });
{% endblock %}
{% block body %}
    <script>
        fbq('track', 'Purchase');
    </script>
    {{ tracking }}
{%  set lines = order.lines|json_decode %}

<?php
function numCharLine($line){
    $alphabet = range('A','Z');
    for($c = 0; $c < count($alphabet); $c++) {
        if( $line > 25 ) {
            $cur_pos = ($line - count($alphabet));
            $new_pos = ($line - count($alphabet)) + 2;
            $num_char_line = $alphabet[$cur_pos] ."". $alphabet[$new_pos];
        } else {
            $num_char_line = $alphabet[$line];
        }
    }
    return $num_char_line;
}
?>
    <div class="wrapper">
        <div class="thank-you-block">
            <div class="thank-you-block--top">
                <h1>{{ language.translate("confirmation_h1") }}</h1>

                <h2>
                    {{ language.translate("confirmation_head", ['value': lottery_name]) }}
                </h2>
                <p>
                    {{ language.translate("confirmation_subhead") }}
                </p>
                {#<div class="countdown">#}
                {##}
                {#</div>#}
                <div class="count">
                    <span class="h4">{{ language.translate("countdown") }}
                        <span class="timer">
                            <div class="countdown-black">
                                <div class="day unit">
                                    <span class="val">%-d {% if show_s_days == '1' %}{{ language.translate("nextDraw_oneday") }}{% else %}{{ language.translate("nextDraw_day") }}{% endif %}</span>
                                </div>
                                <div class="dots"></div>
                                <div class="hour unit">
                                    <span class="val">%-H {{ language.translate("nextDraw_hr") }}</span>
                                </div>
                                <div class="dots">:</div>
                                <div class="minute unit">
                                    <span class="val">%-M {{ language.translate("nextDraw_min") }}</span>
                                </div>
                                {% if show_s_days == '0' %}
                                    <div class="dots">:</div>
                                    <div class="seconds unit">
                                    <span class="val">%-S {{ language.translate("nextDraw_sec") }}</span>
                                </div>
                                {% endif %}
                            </div>
                        </span>
                    </span>

                </div>
                <div class="btn-row">
                    <a href="/{{ language.translate(play_link) }}" class="btn-theme--big">
                        <span class="resizeme">
                            {{ language.translate("tickets_play_again") }}
                        </span>
                    </a>
                </div>
            </div>


            <div class="thank-you-block--jackpot">
                <p>
                    {{ language.translate("confirmation_lines") }} {{ language.translate(draw_day) }}
                    , {{ start_draw_date_format }}
                </p>
                <h2>
                    {{ language.translate("tittle") }} {{ jackpot_value_success }} {% if milliards %}
                        {{ language.translate("billion") }}
                    {% elseif trillions %}
                        {{ language.translate("trillion") }}
                    {% else %}
                        {{ language.translate("million") }}
                    {% endif %}
                </h2>
            </div>
            <div class="thank-you-block--rows">
                {% for i,numbers in lines.bets %}
                    <?php $regular_arr = explode(',', $numbers->regular);
                                                                $lucky_arr = explode(',', $numbers->lucky);
                                                                ?>
                    <div class="thank-you-block--row">
                        <p>
                            <b>{{ language.translate("line_x") }} <?php echo numCharLine($i);?></b> {{ language.translate(draw_day) }}
                            , {{ start_draw_date_format }}
                        </p>

                        <ul class="no-li inline numbers small">
                            {% for regular_number in regular_arr %}
                                 {% if (lottery_name == 'MegaSena') %}
                                    <li class="circle_megasena"><?php echo sprintf("%02s", $regular_number);?></li>
                                 {% else %}
                                    <li><?php echo sprintf("%02s", $regular_number);?></li>
                                 {% endif %}
                            {% endfor %}
                            {% if (lottery_name == 'PowerBall') %}
                                <li class="star_red"><?php echo sprintf("%02s", $lucky_arr[1]);?></li>
                            {% elseif (lottery_name == 'MegaMillions') %}
                                <li class="star_blue" style="color:white;">
                                    <?php echo sprintf("%02s", $lucky_arr[1]);?>
                                </li>
                            {% elseif (lottery_name == 'EuroJackpot') %}
                                {% for lucky_number in lucky_arr %}
                                    <li class="ellipse_eurojackpot">
                                        <?php echo sprintf("%02s", $lucky_number);?>
                                    </li>
                                {% endfor %}
                            {% elseif (lottery_name == 'MegaSena') %}
                               <li class="circle_megasena">
                                   <?php echo sprintf("%02s", $lucky_arr[1]);?>
                               </li>
                            {% else %}
                                {% for lucky_number in lucky_arr %}
                                    <li class="star">
                                        <?php echo sprintf("%02s", $lucky_number);?>
                                    </li>
                                {% endfor %}
                            {% endif %}
                        </ul>

                    </div>
                {% endfor %}
            </div>

            <div class="thank-you-block--bottom">
                <p>
                    {{ language.translate("paragraph1") }}
                </p>
                <h3>
                    {{ language.translate("win_subhead") }}
                </h3>
                <p>
                    {{ language.translate("win_text",['user_email':user.email]) }}
                    {#{{ language.translate("win_text") }}#}
                </p>
            </div>
        </div>
    </div>
    <img id="1000153081_cpa_testing" src="https://ads.trafficjunky.net/tj_ads_pt?a=1000153081&member_id=1000848161&cb={{ random_number }}&epu={{ current_page }}&cti={{ user_email }}&ctv=1&ctd=buyaticket" width="1" height="1" border="0" />
{% endblock %}
