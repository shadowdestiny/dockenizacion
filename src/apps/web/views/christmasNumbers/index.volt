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
                                <h2 class="h2"><span class="purple">{{ language.translate("h2_mainprizes") }}</span>
                                </h2>
                                <p>{{ language.translate("text_mainprizes") }}</p>

                    </div>
                    <div class="cols">
                        <div>
                            <div class="box-current-winners">
                                <table id="current-winners" class="table ui-responsive" data-role="table"
                                       data-mode="reflow">
                                    <thead>
                                    <tr>
                                        <th class="td-ball">{{ language.translate("mainprizes_column0") }}&nbsp;&nbsp;</th>
                                        <th class="td-ball">{{ language.translate("mainprizes_column1") }}&nbsp;&nbsp;</th>
                                        <th class="td-star-ball">{{ language.translate("mainprizes_column2") }}&nbsp;&nbsp;</th>
                                        <th class="td-winners">{{ language.translate("mainprizes_column3") }}&nbsp;&nbsp;</th>
                                        <th class="td-prize">{{ language.translate("mainprizes_column4") }}&nbsp;&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span>{{ language.translate("mainprizes_row1") }}</span>
                                            </td>
                                            <td>
                                                <span>71198</span>
                                            </td>
                                            <td>
                                                <span>1</span>
                                            </td>
                                            <td>
                                                <span>4.000.000  €&nbsp;&nbsp;</span>
                                            </td>
                                            <td>
                                                <span>20.000 / 1€ &nbsp;&nbsp;</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <span>{{ language.translate("mainprizes_row2") }}</span>
                                        </td>
                                        <td>
                                            <span>51244</span>
                                        </td>
                                        <td>
                                            <span>1</span>
                                        </td>
                                        <td>
                                            <span>1.250.000  €&nbsp;&nbsp;</span>
                                        </td>
                                        <td>
                                            <span>6250 / 1€ &nbsp;&nbsp;</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tbody>
                                    <tr>
                                        <td class="td-ball">
                                            <span>{{ language.translate("mainprizes_row3") }}</span>
                                        </td>
                                        <td class="td-ball">
                                            <span>06914</span>
                                        </td>
                                        <td class="td-star-ball">
                                            <span>1</span>
                                        </td>
                                        <td class="td-winners">
                                            <span>500.000 € &nbsp;&nbsp;</span>
                                        </td>
                                        <td class="td-prize">
                                            <span>2.500 / 1€ &nbsp;&nbsp;</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tbody>
                                    <tr>
                                        <td class="td-ball">
                                            <span>{{ language.translate("mainprizes_row4") }}</span>
                                        </td>
                                        <td class="td-ball">
                                            <span>13378 - 61207</span>
                                        </td>
                                        <td class="td-star-ball">
                                            <span>2</span>
                                        </td>
                                        <td class="td-winners">
                                            <span>200.000  €&nbsp;&nbsp;</span>
                                        </td>
                                        <td class="td-prize">
                                            <span>1.000 / 1€ &nbsp;&nbsp;</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tbody>
                                    <tr>
                                        <td class="td-ball">
                                            <span>{{ language.translate("mainprizes_row5") }}</span>
                                        </td>
                                        <td class="td-ball">
                                            <span>03278 - 58808 - 00580 - 05431 - 18065 - 22253 - 24982 - 37872</span>
                                        </td>
                                        <td class="td-star-ball">
                                            <span>8</span>
                                        </td>
                                        <td class="td-winners">
                                            <span>60.000  €&nbsp;&nbsp;</span>
                                        </td>
                                        <td class="td-prize">
                                            <span>300 / 1€ &nbsp;&nbsp;</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="cols">
                        <h2 class="h2"><span
                                    class="purple">{{ language.translate("check_number") }}</span>
                        </h2>
                        <form class="box-add-card form-currency" method="post" action="/christmas-lottery/search">
                            <label for="ticketnumber">{{ language.translate("insert_number") }}&nbsp;</label><input type="text" id="ticket_number" name="ticket_number"/>
                            <input type="submit" class="btn btn-primary" value="Check" />
                        </form>
                    </div>
                    <div class="cols">
                                <h2 class="h2"><span
                                            class="purple">{{ language.translate("h2_otherprizes") }}</span>
                                </h2>
                                <h3 class="h3">{{ language.translate("h3_otherprizesamount") }}</h3>
                                <p>{{ language.translate("text_otherprizesamount") }}</p>

                    </div>
                    <div class="cols">
                        <div class="col8">
                            <div class="box-current-winners">
                                <table id="current-winners" class="table ui-responsive" data-role="table"
                                       data-mode="reflow">
                                    <thead>
                                    <tr>
                                        <th class="td-ball">{{ language.translate("mainprizes_column0") }}</th>
                                        <th class="td-ball">{{ language.translate("mainprizes_column2") }}</th>
                                        <th class="td-star-ball">{{ language.translate("mainprizes_column3") }}</th>
                                        <th class="td-prize">{{ language.translate("mainprizes_column4") }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="td-ball">
                                            <span>{{ language.translate("otherprizes_row1") }}</span>
                                        </td>
                                        <td class="td-ball">
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
                                        <td class="td-ball">
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
                                        <td class="td-ball">
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
                                        <td class="td-ball">
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
                                        <td class="td-ball">
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
                                        <td class="td-ball">
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
                                        <td class="td-ball">
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
                                        <td class="td-ball">
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
                                        <td class="td-ball">
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
                                        <td class="td-ball">
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
                                        <td class="td-ball">
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
                                        <td class="td-ball">
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
                        <div class="col4">
                            <div class="box-history">
                            </div>
                        </div>
                    </div>
                    <div class="cols">
                        <h3 class="h3">{{ language.translate("h3_pedrea") }}</h3>
                        <p>{{ language.translate("text_pedrea") }}</p>
                    </div>
                    {#<div class="cols">#}
                        {#<div class="col8">#}
                            {#<div class="box-current-winners">#}
                                {#<h1 class="h2 purple">{{ language.translate("prizePool_title") }}</h1>#}
                                {#<table id="current-winners" class="table ui-responsive" data-role="table"#}
                                       {#data-mode="reflow">#}
                                    {#<thead>#}
                                    {#<tr>#}
                                        {#<th class="td-ball">{{ language.translate("mainprizes_column1") }}</th>#}
                                        {#<th class="td-ball">{{ language.translate("mainprizes_column1") }}</th>#}
                                        {#<th class="td-star-ball">{{ language.translate("mainprizes_column2") }}</th>#}
                                        {#<th class="td-winners">{{ language.translate("mainprizes_column3") }}</th>#}
                                        {#<th class="td-prize">{{ language.translate("mainprizes_column4") }}</th>#}
                                    {#</tr>#}
                                    {#</thead>#}
                                    {#<tbody>#}
                                    {#<tr>#}
                                        {#<td class="td-ball">#}
                                            {#<span>{{ language.translate("mainprizes_row1") }}</span>#}
                                        {#</td>#}
                                        {#<td class="td-ball">#}
                                            {#<span>{{ language.translate("mainprizes_row1") }}</span>#}
                                        {#</td>#}
                                        {#<td class="td-star-ball">#}
                                            {#<span>{{ language.translate("mainprizes_row1") }}</span>#}
                                        {#</td>#}
                                        {#<td class="td-winners">#}
                                            {#<span>{{ language.translate("mainprizes_row1") }}</span>#}
                                        {#</td>#}
                                        {#<td class="td-prize">#}
                                            {#<span>{{ language.translate("mainprizes_row1") }}</span>#}
                                        {#</td>#}
                                    {#</tr>#}
                                    {#</tbody>#}
                                {#</table>#}
                            {#</div>#}
                        {#</div>#}
                        {#<div class="col4">#}
                            {#<div class="box-history">#}
                            {#</div>#}
                        {#</div>#}
                    {#</div>#}
                    <div class="cols">
                        <h3 class="h3">{{ language.translate("h3_otherprizesclaim") }}</h3>
                        <p>{{ language.translate("text_otherprizesclaim") }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
{% endblock %}
