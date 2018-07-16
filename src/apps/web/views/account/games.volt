{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/account.css">
    <link rel="stylesheet" href="/a/css/pagination.css">
{% endblock %}
{% block bodyClass %}games{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": "account"}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}
{% block template_scripts_code %}
    function euromillionsPlay(numbers,lottery)
    {
        var storageNumbers = '[', playConfigs = numbers.split("|");

        playConfigs.forEach(function(element) {
            playConfig = element.split(".");
            storageNumbers += '{"numbers":['+playConfig[0]+'],"stars":['+playConfig[1]+']},';
        });

        storageNumbers = storageNumbers.slice(0, -1) + ']';

        localStorage.setItem('bet_line', storageNumbers);
        if(lottery === 'EuroMillions') {
            window.location.href = '/{{ language.translate('link_euromillions_play') }}';
        } elseif(lottery === 'PowerBall') {
            window.location.href = '/{{ language.translate('link_powerball_play') }}';
        }

    }
{% endblock %}
{% block body %}
    <main id="content" class="account-page tickets-page">
        <div class="wrapper">
            {% include "account/_breadcrumbs.volt" %}
            <div class="nav">
                {% set activeSubnav='{"myClass": "games"}'|json_decode %}
                <div class="dashboard-menu--desktop">
                    {% include "account/_nav.volt" %}
                </div>

                <div class="dashboard-menu--mobile--back dashboard-menu--mobile--back--tickets">
                    <a href="/account/wallet">
                        {{ language.translate("myAccount_tickets") }}
                    </a>
                </div>

            </div>

            <div class="content">

                <div class="nav nav--tickets--mobile">
                    <div class="dashboard-menu--mobile dashboard-menu--mobile--tickets-menu">
                        {% include "account/_nav_mob_tickets.volt" %}
                    </div>
                </div>


                {#Clear html start#}
                <form action="" class="tickets-form">

                    {% if my_games_actives is not empty %}
                        <div class="tickets-blocker tickets-blocker--table-03">

                            <div class="nav">
                                <div class="dashboard-menu--mobile--back dashboard-menu--mobile--back--submenu">
                                    <a href="#">
                                        {{ language.translate("tickets_upcoming") }}
                                    </a>
                                </div>
                            </div>

                            <h3>
                                {{ language.translate("tickets_upcoming") }}
                            </h3>

                            {#{% include "_elements/tickets-filter.volt" %}#}
                            {% include "_elements/tickets-table-euromillions-active.volt" %}
                        </div>
                    {% endif %}


                    {% if my_subscription_actives is not empty %}
                    <div class="tickets-blocker tickets-blocker--table-01">

                        <div class="nav">
                        <div class="dashboard-menu--mobile--back dashboard-menu--mobile--back--submenu">
                            <a href="#">
                                {{ language.translate("tickets_SubsUpcoming") }}
                            </a>
                        </div>
                        </div>

                        <h3>
                            {{ language.translate("tickets_SubsUpcoming") }}
                        </h3>

                        {#{% include "_elements/tickets-filter.volt" %}#}
                        {% include "_elements/tickets-table-subscription-active.volt" %}

                    </div>
                    {% endif %}

                    {% if my_games_inactives is not empty %}
                        <div class="tickets-blocker tickets-blocker--table-06">

                            <div class="nav">
                                <div class="dashboard-menu--mobile--back dashboard-menu--mobile--back--submenu">
                                    <a href="#">
                                        {{ language.translate("tickets_past") }}
                                    </a>
                                </div>
                            </div>

                            <h3>
                                {{ language.translate("tickets_past") }}
                            </h3>

                            {#{% include "_elements/tickets-filter.volt" %}#}
                            {% include "_elements/tickets-table-euromillions-inactive.volt" %}
                        </div>
                    {% endif %}

                    {% if my_subscription_inactives is not empty %}
                        <div class="tickets-blocker tickets-blocker--table-04">

                            <div class="nav">
                                <div class="dashboard-menu--mobile--back dashboard-menu--mobile--back--submenu">
                                    <a href="#">
                                        {{ language.translate("tickets_SubsPast") }}
                                    </a>
                                </div>
                            </div>

                            <h3>
                                {{ language.translate("tickets_SubsPast") }}
                            </h3>

                            {#{% include "_elements/tickets-filter.volt" %}#}
                            {% include "_elements/tickets-table-subscription-inactive.volt" %}

                        </div>
                    {% endif %}


                    {% if my_christmas_actives is not empty %}
                    <div class="tickets-blocker tickets-blocker--table-02">

                        <div class="nav">
                        <div class="dashboard-menu--mobile--back dashboard-menu--mobile--back--submenu">
                            <a href="#">
                                {{ language.translate("My Christmas Tickets") }}
                            </a>
                        </div>
                        </div>

                        <h3>
                            {{ language.translate("My Christmas Tickets") }}
                        </h3>

                        {#{% include "_elements/tickets-filter.volt" %}#}
                        {% include "_elements/tickets-table-christmas-active.volt" %}

                    </div>
                    {% endif %}



                    {% if my_christmas_inactives is not empty %}
                        <div class="tickets-blocker tickets-blocker--table-05">

                            <div class="nav">
                                <div class="dashboard-menu--mobile--back dashboard-menu--mobile--back--submenu">
                                    <a href="#">
                                        {{ language.translate("My Past Christmas Tickets") }}
                                    </a>
                                </div>
                            </div>

                            <h3>
                                {{ language.translate("My Past Christmas Tickets") }}
                            </h3>

                            {#{% include "_elements/tickets-filter.volt" %}#}
                            {% include "_elements/tickets-table-christmas-inactive.volt" %}

                        </div>
                    {% endif %}


                </form>
            </div>
        </div>
    </main>
{% endblock %}
