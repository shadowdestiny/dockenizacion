{% extends "main.volt" %}
{% block template_css %}
    <link rel="stylesheet" href="/w/css/numbers.css">
    <link Rel="Canonical" href="{{ language.translate('canonical_christmas_results') }}" />
{% endblock %}
{% block bodyClass %}terms{% endblock %}
{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %}
    {% include "_elements/header.volt" %}
{% endblock %}
{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}
{% block template_scripts %}<script src="/w/js/mobileFix.min.js"></script>{% endblock %}

{% block body %}
    <main id="content">
        <div class="wrapper">
            <div class="box-basic">
                <h1 class="h1 title">{{ language.translate("h1_christmasresults") }}</h1>
                <div class="wrap">
                    <div class="cols">
                        {% if ticket_number != null %}
                        <h2 class="h2"><span class="purple">{{ language.translate("text_winner") }}</span>
                        </h2>
                        <br />
                        <div class="content cl">
                            <table id="current-winners" class="table ui-responsive" data-role="table"
                                   data-mode="reflow">
                                <thead>
                                <tr>
                                    <th class="td-ball">{{ language.translate("text_number") }}&nbsp;&nbsp;</th>
                                    <th class="td-ball">{{ language.translate("prize") }}&nbsp;&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
                                {% for ticket in ticket_number %}
                                    <tr>
                                        <td>
                                            <span class="detail">{{ ticket['number'] }}</span>
                                        </td>
                                        <td>
                                            <span class="detail">{{ ticket['prize'] / 100 }} €</span>
                                        </td>
                                    </tr>
                                {% endfor %}
                                </tbody>
                            </table>
                        </div>
                        {% else %}
                            <h2 class="h2"><span class="purple">{{ language.translate("text_loser") }}</span>
                            </h2>
                            <br />
                            <div class="col8">
                                <div class="content cl">
                                </div>
                            </div>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    </main>
{% endblock %}
