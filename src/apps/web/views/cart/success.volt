{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/cart.css">
{% endblock %}
{% block template_scripts %}{% endblock %}
{% block bodyClass %}cart success minimal{% endblock %}

{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic medium">
            <div class="cols">
                <div class="col7 txt-col">
                    <h1 class="h1 title yellow">{{ language.translate("Thanks for your order") }}</h1>
                    <h2 class="h2 sub-title purple">{{ language.translate("You just completed your payment") }}</h2>


                    <p class="note">
                        <strong>{{ language.translate("NOTE") }}</strong><br>
                        {{ language.translate("For long durations plays of your numbers a portion of your payment we'll be added directly on your Wallet Balance to be used to pay the future Draws.") }}
                    </p>

                    <div class="countdown">
                        <span class="h4">{{ language.translate("Countdown to the next draw:") }}</span>
                        <span class="purple">{{ language.translate("3 Days 05:29") }}</span>
                    </div>

                    <p>{{ language.translate('We just sent your an email with a resume of the number played, or if you wish you can always <a href="javascript:void(0);">print this ticket</a>') }}</p>

                    <h2 class="h4">{{ language.translate("In case of winning") }}</h2>
                    <p>{{ language.translate("We'll contact you at <em>email@email.com</em> be sure to add our email <em>support@euromillion.com</em> to your address book to avoid spam filters.") }}</p>

                    <h2 class="h4">{{ language.translate("What would you do with your winnings?") }}</h2>
                    <p class="small-margin">{{ language.translate("We are very curious to know what makes you play and what are your dreams of victory. We would be very happy to hear from you and inspire us with your story and experience of playing the lottery.")}}</p>
                    <form novalidate class="form">
                        <textarea class="textarea" placeholder="{{ language.translate('What makes you play? What would you do if you won the big Euromillion jackpot?')}}"></textarea>
                        <input type="submit" class="btn blue submit" value="{{ language.translate('Share with us') }}">
                    </form>
                </div>
                <div class="col5 ticket-col">
                    <div class="bg-ticket">
                        <div class="results">
                            *******************************<br>
                            <span class="title">{{ language.translate("Jackpot &euro; 100,000,000") }}</span>
                            <br>*******************************
                            <br>
                            <ul class="no-li num">
                                <li>A</li><li>04</li><li>14</li><li>21</li><li>36</li><li>38</li><li class="yellow">07</li><li class="yellow">10</li>
                            </ul>
                            <ul class="no-li num">
                                <li>B</li><li>05</li><li>17</li><li>19</li><li>31</li><li>45</li><li class="yellow">03</li><li class="yellow">04</li><li class="yellow">09</li>
                            </ul>
                            <ul class="no-li num">
                                <li>C</li><li>02</li><li>12</li><li>17</li><li>19</li><li>27</li><li>36</li><li>42</li><li>44</li><li class="yellow">05</li><li class="yellow">11</li>
                            </ul>
                            <br>===============================
                            <br><em class="luck">{{ language.translate("Good luck for your draw on")}}
                            <br>Wed 09th Sep 2015</em>
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