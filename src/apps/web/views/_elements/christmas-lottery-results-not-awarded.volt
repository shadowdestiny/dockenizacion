<div class="christmass-lottery-results-prize--page">

    <div class="title-block">
        <div class="wrapper">
            <h1>
                {% if mobile == 1 %}
                    {{ language.translate("h1_resultsch_mobile") }}
                {% else %}
                    {{ language.translate("h1_christmasresults") }}
                {% endif %}
            </h1>

        </div>
    </div>

    <div class="wrapper">

        <div class="main-text">
            <h2>{{ language.translate("text_winner") }}
            </h2>

        </div>

        <div class="winners-block">
            {% for ticket in ticket_number %}
                <div class="block">
                    <div class="block-head">{{ language.translate("text_number") }}</div>
                    <div class="block-data">{{ ticket['number'] }}</div>
                </div>
                <div class="block">
                    <div class="block-head">{{ language.translate("prize") }}</div>
                    <div class="block-data">{{ ticket['prize'] / 100 }} €</div>
                </div>
            {% endfor %}
        </div>

    </div>

</div>