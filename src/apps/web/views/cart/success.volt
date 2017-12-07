{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/cart.css">{% endblock %}
{% block template_scripts %}{% endblock %}
{% block bodyClass %}
cart success minimal
{% include "_elements/jlength.volt" %}
{% endblock %}

{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script><script>if (window!=top){top.location.href=location.href;}localStorage.removeItem('bet_line')</script>{% endblock %}

{% block body %}


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
                <div class="count">
                    Countdown to next draw is 7 hours and 11 minutes
                </div>
                <div class="btn-row">
                    <a href="#" class="btn-theme--big">Play more</a>
                </div>
            </div>


            <div class="thank-you-block--jackpot">
                <p>
                    your lines played for this friday’s draw 28 may 2017
                </p>
                <h2>
                    jackpot €70 milliones
                </h2>
            </div>

            <div class="thank-you-block--rows">
                <div class="thank-you-block--row">
                    <p>
                        <b>LINE A</b> Tuesday, 23.01.2017
                    </p>

                    <ul class="no-li inline numbers small">

                        <li>9</li>
                        <li>12</li>
                        <li>29</li>
                        <li>39</li>
                        <li>45</li>
                        <li class="star">5</li>
                        <li class="star">12</li>

                    </ul>


                </div>
                <div class="thank-you-block--row">
                    <p>
                        <b>LINE A</b> Tuesday, 23.01.2017
                    </p>

                    <ul class="no-li inline numbers small">

                        <li>9</li>
                        <li>12</li>
                        <li>29</li>
                        <li>39</li>
                        <li>45</li>
                        <li class="star">5</li>
                        <li class="star">12</li>

                    </ul>


                </div>
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

<main id="content">
    <div class="wrapper">
        <div class="box-basic medium">
            <div class="cols">
                <div class="col7 txt-col">
                    <h1 class="h1 title yellow">{{ language.translate("confirmation_head") }}</h1>
                    <h2 class="h2 sub-title purple">{{ language.translate("confirmation_subhead") }}</h2>

                    {% if order.frequency > 1 %}
                    <p class="note">
                        <strong class="b">{{ language.translate("NOTE") }}</strong>
                        <span class="txt">{{ language.translate("We have just charged you for your first participation and the remaining of your payment is in your account balance. For your convenience, future participations will be charged directly from your account balance before the draw.") }}</span>
                    </p>
                    {% endif %}
                    <div class="countdown">
                        <span class="h4">{{ language.translate("countdown") }}</span>
                        <span class="purple">{{ countdown_next_draw }}</span>
                    </div>

                    <p>{{ language.translate("paragraph1") }} </p>

                    <h2 class="h4">{{ language.translate("win_subhead") }}</h2>
                    <p>{{ language.translate("win_text",['useremail' : user.email]) }}</p>

                    {#<h2 class="h4">{{ language.app("What would you do with your winnings?") }}</h2>
                    <p class="small-margin">{{ language.app("We are very curious to know what makes you play and what are your dreams of victory. We would be very happy to hear from you and inspire us with your story and experience of playing the lottery.")}}</p>
                    <form novalidate class="form">
                        <textarea class="textarea" placeholder="{{ language.app('What makes you play? What would you do if you won the big Euromillions jackpot?')}}"></textarea>
                        <input type="submit" class="btn blue submit" value="{{ language.app('Share with us') }}">
                    </form>#}
                </div>
                <div class="col5 ticket-col">
                    <div class="bg-ticket">
                        <div class="results">
                            *******************************<br>
                            <span class="title">{{ jackpot_value }}</span>
                            <br>*******************************
                            <br>
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
                            {%  for i,numbers in lines.bets %}
                                <?php $regular_arr = explode(',', $numbers->regular);
                                      $lucky_arr = explode(',', $numbers->lucky);
                                ?>
                                <ul class="no-li num">
                                    <li><?php echo numCharLine($i);?></li>
                                    {% for regular_number in regular_arr %}
                                        <li><?php echo sprintf("%02s", $regular_number);?></li>
                                    {% endfor %}
                                    {% for lucky_number in lucky_arr %}
                                        <li class="yellow"><?php echo sprintf("%02s", $lucky_number);?></li>
                                    {% endfor %}
                                </ul>
                            {% endfor %}
                            <br>===============================
                            <br><em class="luck">{{ language.translate("goodLuck")}}
                            <br>{{ start_draw_date_format }}</em>
                            <br>===============================
                            <br><div class="txt-logo">EuroMillions.com</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
{% endblock %}
