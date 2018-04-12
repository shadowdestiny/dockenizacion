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
        <div class="christmass-lottery-results--page">

            <div class="title-block">
                <div class="wrapper">
                <h1>
                    {% if mobile == 1 %}
                        {{ language.translate("h1_resultsch_mobile") }}
                    {% else %}
                        {{ language.translate("h1_christmasresults") }}
                    {% endif %}
                </h1>

                <p>
                    {% if mobile == 1 %}
                        {{ language.translate("h1_resultsch_mobile") }}
                    {% else %}
                        {{ language.translate("h1_christmasresults") }}
                    {% endif %}
                </p>
            </div>
            </div>

        <div class="wrapper">


            <div class="main-text">
                <h2><span>{{ language.translate("h2_mainprizes") }}</span>
                </h2>
                <p>
                    {{ language.translate("text_mainprizes") }}
                </p>
                
            </div>



            <div class="current-winners-block">
                <p class="mobile-title">
                    The Christmas Lottery results <br>
                    for 22 december 2017
                </p>
                <div class="box-current-winners">
                <table id="current-winners" class="table ui-responsive" data-role="table"
                       data-mode="reflow">
                    <thead>
                    <tr>
                        <th class="td-main-prize">{{ language.translate("mainprizes_column0") }}&nbsp;&nbsp;</th>
                        <th class="td-ball">{{ language.translate("mainprizes_column1") }}&nbsp;&nbsp;</th>
                        <th class="td-star-ball">{{ language.translate("mainprizes_column2") }}&nbsp;&nbsp;</th>
                        <th class="td-winners">{{ language.translate("mainprizes_column3") }}&nbsp;&nbsp;</th>
                        <th class="td-prize">{{ language.translate("mainprizes_column4") }}&nbsp;&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="td-main-prize">
                            <span>{{ language.translate("mainprizes_row1") }}</span>
                        </td>
                        <td class="td-ball">
                            <span>71198</span>
                        </td>
                        <td class="td-star-ball">
                            <span>1</span>
                        </td>
                        <td class="td-main-prize--mobile">
                            <span>{{ language.translate("mainprizes_row1") }}</span>
                        </td>
                        <td class="td-winners">
                            <span>4.000.000  €</span>
                        </td>
                        <td class="td-prize">
                            <span>20.000 / 1€</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="td-main-prize">
                            <span>{{ language.translate("mainprizes_row2") }}</span>
                        </td>
                        <td class="td-ball">
                            <span>51244</span>
                        </td>
                        <td class="td-star-ball">
                            <span>1</span>
                        </td>
                        <td class="td-main-prize--mobile">
                            <span>{{ language.translate("mainprizes_row1") }}</span>
                        </td>
                        <td class="td-winners">
                            <span>1.250.000  €</span>
                        </td>
                        <td class="td-prize">
                            <span>6250 / 1€</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="td-main-prize">
                            <span>{{ language.translate("mainprizes_row3") }}</span>
                        </td>
                        <td class="td-ball">
                            <span>06914</span>
                        </td>
                        <td class="td-star-ball">
                            <span>1</span>
                        </td>
                        <td class="td-main-prize--mobile">
                            <span>{{ language.translate("mainprizes_row1") }}</span>
                        </td>
                        <td class="td-winners">
                            <span>500.000 €</span>
                        </td>
                        <td class="td-prize">
                            <span>2.500 / 1€</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="td-main-prize">
                            <span>{{ language.translate("mainprizes_row4") }}</span>
                        </td>
                        <td class="td-ball">
                            <span>13378 - 61207</span>
                        </td>
                        <td class="td-star-ball">
                            <span>2</span>
                        </td>
                        <td class="td-main-prize--mobile">
                            <span>{{ language.translate("mainprizes_row1") }}</span>
                        </td>
                        <td class="td-winners">
                            <span>200.000  €</span>
                        </td>
                        <td class="td-prize">
                            <span>1.000 / 1€</span>
                        </td>
                    </tr>

                    <tr>
                        <td class="td-main-prize">
                            <span>{{ language.translate("mainprizes_row5") }}</span>
                        </td>
                        <td class="td-ball">
                            <span>03278 - 58808 - 00580 - 05431 - 18065 - 22253 - 24982 - 37872</span>
                        </td>
                        <td class="td-star-ball">
                            <span>8</span>
                        </td>
                        <td class="td-main-prize--mobile">
                            <span>{{ language.translate("mainprizes_row1") }}</span>
                        </td>
                        <td class="td-winners">
                            <span>60.000  €</span>
                        </td>
                        <td class="td-prize">
                            <span>300 / 1€</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            </div>

            <div class="did-you-win-block">
                <h2>Did you win?
                </h2>
                <h3>{{ language.translate("check_number") }}
                </h3>
                <form class="box-add-card form-currency" method="post" action="/christmas-lottery/search">
                    <label for="ticketnumber">{{ language.translate("insert_number") }}&nbsp;</label><input type="text" id="ticket_number" name="ticket_number"/>
                    <input type="submit" class="btn btn-primary" value="Check" />
                </form>
            </div>

            <div class="other-prizes-text">
                <h2><span>{{ language.translate("h2_otherprizes") }}</span>
                </h2>
                <h3>{{ language.translate("h3_otherprizesamount") }}</h3>
                <p>
                    {{ language.translate("text_otherprizesamount") }}
                </p>
            </div>

            <div class="other-prizes-block">
                <div class="box-current-winners">
                    <table id="current-winners" class="table ui-responsive" data-role="table"
                           data-mode="reflow">
                        <thead>
                        <tr>
                            <th class="td-ball">{{ language.translate("mainprizes_column0") }}</th>
                            <th class="td-ball-2">{{ language.translate("mainprizes_column2") }}</th>
                            <th class="td-star-ball">{{ language.translate("mainprizes_column3") }}</th>
                            <th class="td-prize">{{ language.translate("mainprizes_column4") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row1") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>1.774</span>
                            </td>
                            <td class="td-star-ball">
                                <span>1.000 €</span>
                            </td>
                            <td class="td-winners">
                                <span>5€ / 1€</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row2") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>2</span>
                            </td>
                            <td class="td-star-ball">
                                <span>20.000 €</span>
                            </td>
                            <td class="td-winners">
                                <span>100€ / 1€</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row3") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>2</span>
                            </td>
                            <td class="td-star-ball">
                                <span>12.500 €</span>
                            </td>
                            <td class="td-winners">
                                <span>62,5€ / 1€</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row4") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>2</span>
                            </td>
                            <td class="td-star-ball">
                                <span>9.600 €</span>
                            </td>
                            <td class="td-winners">
                                <span>48€ / 1€</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row5") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>99</span>
                            </td>
                            <td class="td-star-ball">
                                <span>1.000 €</span>
                            </td>
                            <td class="td-winners">
                                <span>5€ / 1€</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row6") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>99</span>
                            </td>
                            <td class="td-star-ball">
                                <span>1.000 €</span>
                            </td>
                            <td class="td-winners">
                                <span>5€ / 1€</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row7") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>99</span>
                            </td>
                            <td class="td-star-ball">
                                <span>1.000 €</span>
                            </td>
                            <td class="td-winners">
                                <span>5€ / 1€</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row8") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>198</span>
                            </td>
                            <td class="td-star-ball">
                                <span>1.000 €</span>
                            </td>
                            <td class="td-winners">
                                <span>5€ / 1€</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row9") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>999</span>
                            </td>
                            <td class="td-star-ball">
                                <span>1.000 €</span>
                            </td>
                            <td class="td-winners">
                                <span>6€ / 1€</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row10") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>999</span>
                            </td>
                            <td class="td-star-ball">
                                <span>1.000 €</span>
                            </td>
                            <td class="td-winners">
                                <span>5€ / 1€</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row11") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>999</span>
                            </td>
                            <td class="td-star-ball">
                                <span>1.000 €</span>
                            </td>
                            <td class="td-winners">
                                <span>5€ / 1€</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-ball">
                                <span>{{ language.translate("otherprizes_row12") }}</span>
                            </td>
                            <td class="td-ball-2">
                                <span>9.999</span>
                            </td>
                            <td class="td-star-ball">
                                <span>200 €</span>
                            </td>
                            <td class="td-winners">
                                <span>1€ / 1€</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="texter">
                <div class="block--text--accordion">
                    <h3>{{ language.translate("h3_pedrea") }}</h3>
                    <p>
                        {{ language.translate("text_pedrea") }}
                    </p>
                </div>
                <div class="block--text--accordion">
                    <h3>{{ language.translate("h3_otherprizesclaim") }}</h3>
                    <p>
                        {{ language.translate("text_otherprizesclaim") }}
                    </p>
                </div>
            </div>

        </div>

        </div>



        {#TODO: add this elemet on the right page#}
        {% include "_elements/christmass-lottery-results-prize.volt" %}


    </main>
{% endblock %}
