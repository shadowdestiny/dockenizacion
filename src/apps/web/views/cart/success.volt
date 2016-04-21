{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/cart.css">{% endblock %}
{% block template_scripts %}{% endblock %}
{% block bodyClass %}
cart success minimal
{% include "_elements/jlength.volt" %}
{% endblock %}

{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script><script>localStorage.removeItem('bet_line')</script>{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic medium">
            <div class="cols">
                <div class="col7 txt-col">
                    <h1 class="h1 title yellow">{{ language.translate("Thanks for your order") }}</h1>
                    <h2 class="h2 sub-title purple">{{ language.translate("You just completed your payment") }}</h2>

                    {% if order.frequency > 1 %}
                    <p class="note">
                        <strong class="b">{{ language.translate("NOTE") }}</strong>
                        <span class="txt">{{ language.translate("We have just charged you for your first participation and the remaining of your payment is in your account balance. For your convenience, future participations will be charged directly from your account balance before the draw.") }}</span>
                    </p>
                    {% endif %}
                    <div class="countdown">
                        <span class="h4">{{ language.translate("Countdown to the next draw:") }}</span>
                        <span class="purple">{{ countdown_next_draw }}</span>
                    </div>

                    <p>{{ language.translate('We just sent your an email with a resume of the number played, or if you wish you can always <a href="javascript:void(0);">print this ticket</a>') }}</p>

                    <h2 class="h4">{{ language.translate("In case of winning") }}</h2>
                    <p>{{ language.translate("We'll contact you at <em>%useremail%</em> be sure to add our email <em>support@euromillion.com</em> to your address book to avoid spam filters.",['useremail' : user.email]) }}</p>

                    {#<h2 class="h4">{{ language.translate("What would you do with your winnings?") }}</h2>
                    <p class="small-margin">{{ language.translate("We are very curious to know what makes you play and what are your dreams of victory. We would be very happy to hear from you and inspire us with your story and experience of playing the lottery.")}}</p>
                    <form novalidate class="form">
                        <textarea class="textarea" placeholder="{{ language.translate('What makes you play? What would you do if you won the big Euromillion jackpot?')}}"></textarea>
                        <input type="submit" class="btn blue submit" value="{{ language.translate('Share with us') }}">
                    </form>#}
                </div>
                <div class="col5 ticket-col">
                    <div class="bg-ticket">
                        <div class="results">
                            *******************************<br>
                            <span class="title">{{ jackpot }}</span>
                            <br>*******************************
                            <br>
                            {%  set lines = order.lines|json_decode %}
                            <?php $chars = range('A','Z'); ?>
                            {%  for i,numbers in lines.bets %}
                                <?php $regular_arr = explode(',', $numbers->regular);
                                      $lucky_arr = explode(',', $numbers->lucky);
                                ?>
                                <ul class="no-li num">
                                    <li><?php echo $chars[$i];?></li>
                                    {% for regular_number in regular_arr %}
                                        <li><?php echo sprintf("%02s", $regular_number);?></li>
                                    {% endfor %}
                                    {% for lucky_number in lucky_arr %}
                                        <li class="yellow"><?php echo sprintf("%02s", $lucky_number);?></li>
                                    {% endfor %}
                                </ul>
                            {% endfor %}
                            <br>===============================
                            <br><em class="luck">{{ language.translate("Good luck for your draw on")}}
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