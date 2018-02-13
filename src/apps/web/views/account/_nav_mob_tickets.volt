<ul class="no-li">
    {% if my_subscription_actives is not empty %}
    <li>
        <a class="dashboard-menu--my-subscriptions" data-role="tickets-blocker--table-01" href="#">
            {{ language.translate("tickets_SubsUpcoming") }}
        </a>
    </li>
    {% endif %}

    {% if my_christmas_actives is not empty %}
    <li>
        <a class="dashboard-menu--my-subscriptions" data-role="tickets-blocker--table-02" href="#">
            {{ language.translate("My Christmas Tickets") }}
        </a>
    </li>
    {% endif %}

    {% if my_games_actives is not empty %}
    <li>
        <a class="dashboard-menu--my-subscriptions" data-role="tickets-blocker--table-03" href="#">
            {{ language.translate("tickets_upcoming") }}
        </a>
    </li>
    {% endif %}

    {% if my_subscription_inactives is not empty %}
    <li>
        <a class="dashboard-menu--my-subscriptions" data-role="tickets-blocker--table-04" href="#">
            {{ language.translate("tickets_SubsPast") }}
        </a>
    </li>
    {% endif %}

    {% if my_christmas_inactives is not empty %}
    <li>
        <a class="dashboard-menu--my-subscriptions" data-role="tickets-blocker--table-05" href="#">
            {{ language.translate("My Past Christmas Tickets") }}
        </a>
    </li>
    {% endif %}

    {% if my_games_inactives is not empty %}
    <li>
        <a class="dashboard-menu--my-subscriptions" data-role="tickets-blocker--table-06" href="#">
            {{ language.translate("tickets_past") }}
        </a>
    </li>
    {% endif %}
</ul>
