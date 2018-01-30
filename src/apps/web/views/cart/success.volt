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
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script><script>if (window!=top){top.location.href=location.href;}localStorage.removeItem('bet_line')</script>
{% endblock %}
{% block template_scripts_code %}
    $(function(){
    var html_formatted_offset = [];
    $('.countdown .dots').eq(2).hide();
    $('.countdown .seconds').hide();
    var element = $('.countdown');
    var html_formatted = element.html();
    $('.countdown .dots').eq(2).show();
    $('.countdown .seconds').show();
    $('.countdown .day').remove();
    $('.countdown .dots').eq(0).remove();
    html_formatted_offset[0] = $('.countdown').html();
    $('.countdown .hour').remove();
    $('.countdown .dots').eq(0).remove();
    html_formatted_offset[1] = $('.countdown').html();
    $('.countdown .minute').remove();
    $('.countdown .dots').eq(0).remove();
    html_formatted_offset[2] = $('.countdown').html();
    var finish_action = function(){
    $('.box-next-draw .btn.red').remove();
    }
    var date = '{{ date_draw }}'; {# To test "2015/11/17 10:49:00"  #}
    var finish_text = "<div class='closed'>{{ language.translate('The Draw is closed') }}</div>";
    count_down(element,html_formatted,html_formatted_offset, date,finish_text, finish_action);
    });
{% endblock %}
{% block body %}

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
                <h1>Thank you!</h1>

                <h2>
                    thanks for your order and good luck!
                </h2>
                <p>
                    You just completed your payment
                </p>
                {#<div class="countdown">#}
                    {##}
                {#</div>#}
                <div class="count">
                    <span class="h4">{{ language.translate("countdown") }}</span>
                    <span class="purple"><div class="countdown">
                        <div class="day unit">
                            <span class="val">%-d {% if show_s_days == '1' %}DAY{% else %}DAYS{% endif %}</span>
                        </div>
                        <div class="dots"></div>
                        <div class="hour unit">
                            <span class="val">%-H HRS</span>
                        </div>
                        <div class="dots">:</div>
                        <div class="minute unit">
                            <span class="val">%-M MIN</span>
                        </div>
                            {% if show_s_days == '0' %}
                                <div class="dots">:</div>
                                <div class="seconds unit">
                                <span class="val">%-S SECS</span>
                            </div>
                            {% endif %}
                    </div></span>

                </div>
                <div class="btn-row">
                    <a href="#" class="btn-theme--big">Play more</a>
                </div>
            </div>


            <div class="thank-you-block--jackpot">
                <p>
                    your lines played for this {{ start_draw_date_format }}
                </p>
                <h2>
                    jackpot {{ jackpot_value }} milliones
                </h2>
            </div>

            <div class="thank-you-block--rows">
                {%  for i,numbers in lines.bets %}
                    <?php $regular_arr = explode(',', $numbers->regular);
                        $lucky_arr = explode(',', $numbers->lucky);
                    ?>
                <div class="thank-you-block--row">
                    <p>
                        <b>LINE <?php echo numCharLine($i);?></b> {{ start_draw_date_format }}
                    </p>

                    <ul class="no-li inline numbers small">
                        {% for regular_number in regular_arr %}
                            <li><?php echo sprintf("%02s", $regular_number);?></li>
                        {% endfor %}
                        {% for lucky_number in lucky_arr %}
                            <li class="star"><?php echo sprintf("%02s", $lucky_number);?></li>
                        {% endfor %}
                    </ul>
                </div>
                {% endfor %}
            </div>

            <div class="thank-you-block--bottom">
                <p>
                    We have sent you an email with details of the line you played. <br>
                    You can also see the lines you have played on our <a href="#">tickets page</a>
                </p>
                <h3>
                    In case of winning
                </h3>
                <p>
                    We will contact you at <a href="mailto:joseluis@panamedia.net">joseluis@panamedia.net</a> be sure to add our
                    <br>
                    email <a href="mailto:support@euromillions.com">support@euromillions.com</a> to your adress book to avoid spam
                    filters.
                </p>
            </div>
        </div>
    </div>

{% endblock %}
