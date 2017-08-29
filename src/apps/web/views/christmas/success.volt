{% extends "main.volt" %}
{% block template_css %}<link rel="stylesheet" href="/w/css/cart.css">{% endblock %}
{% block template_scripts %}{% endblock %}
{% block bodyClass %}
cart success minimal
{% include "_elements/jlength.volt" %}
{% endblock %}

{% block header %}{% include "_elements/minimal-header.volt" %}{% endblock %}
{% block footer %}{% include "_elements/minimal-footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script><script>if(window!=top){top.location.href=location.href;}</script>{% endblock %}

{% block body %}
<main id="content">
    <div class="wrapper">
        <div class="box-basic medium">
            <div class="cols">
                <div class="col7 txt-col">
                    <h1 class="h1 title yellow">{{ language.translate("Thanks for your order") }}</h1>
                    <h2 class="h2 sub-title purple">{{ language.translate("You just completed your payment") }}</h2>

                    <p>{{ language.translate('We have sent you an email with details of the ticket you played. You can also see the tickets you have played on our <a href="/profile/tickets/games">tickets page</a>') }} </p>

                    <h2 class="h4">{{ language.translate("In case of winning") }}</h2>
                    <p>{{ language.translate("We'll contact you at <em>%useremail%</em> be sure to add our email <em>support@euromillions.com</em> to your address book to avoid spam filters.",['useremail' : user.getEmail()]) }}</p>

                </div>
                <div class="col5 ticket-col">
                    <div class="bg-ticket">
                        <div class="results">
                            <br>
                            Christmas Tickets
                            {%  for ticket in christmasTickets %}
                                <ul class="no-li num">
                                    <li>{{ ticket.getNumber() }}</li>
                                </ul>
                            {% endfor %}
                            <br>===============================
                            <br><em class="luck">{{ language.translate("Good luck for your ticket on")}}
                            <br>{{ draw_date_format }}</em>
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
