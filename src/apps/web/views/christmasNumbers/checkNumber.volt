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
                        <div class="col8">
                            <div class="content cl">
                                <h2 class="h2"><span
                                            class="purple">{{ language.translate("h2_mainprizes") }}</span>
                                </h2>
                                <p>{{ language.translate("text_mainprizes") }}</p>

                            </div>
                        </div>
                    </div>
                    <div class="cols">
                        <div class="col8">
                            <div class="box-current-winners">
                                <h1 class="h2 purple">{{ language.translate("prizePool_title") }}</h1>
                                <table id="current-winners" class="table ui-responsive" data-role="table"
                                       data-mode="reflow">
                                    <thead>
                                    <tr>
                                        <th class="td-ball">{{ language.translate("mainprizes_column1") }}</th>
                                        <th class="td-ball">{{ language.translate("mainprizes_column1") }}</th>
                                        <th class="td-star-ball">{{ language.translate("mainprizes_column2") }}</th>
                                        <th class="td-winners">{{ language.translate("mainprizes_column3") }}</th>
                                        <th class="td-prize">{{ language.translate("mainprizes_column4") }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="td-ball">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-ball">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-star-ball">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-winners">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-prize">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
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
                        <div class="col8">
                            <div class="content cl">
                                <h2 class="h2"><span
                                            class="purple">{{ language.translate("h2_otherprizes") }}</span>
                                </h2>
                                <h3 class="h3">{{ language.translate("h3_otherprizesamount") }}</h3>
                                <p>{{ language.translate("text_otherprizesamount") }}</p>

                            </div>
                        </div>
                    </div>
                    <div class="cols">
                        <div class="col8">
                            <div class="box-current-winners">
                                <h1 class="h2 purple">{{ language.translate("prizePool_title") }}</h1>
                                <table id="current-winners" class="table ui-responsive" data-role="table"
                                       data-mode="reflow">
                                    <thead>
                                    <tr>
                                        <th class="td-ball">{{ language.translate("mainprizes_column1") }}</th>
                                        <th class="td-ball">{{ language.translate("mainprizes_column1") }}</th>
                                        <th class="td-star-ball">{{ language.translate("mainprizes_column2") }}</th>
                                        <th class="td-winners">{{ language.translate("mainprizes_column3") }}</th>
                                        <th class="td-prize">{{ language.translate("mainprizes_column4") }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="td-ball">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-ball">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-star-ball">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-winners">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-prize">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
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
                        <div class="col8">
                            <div class="content cl">

                                <h3 class="h3">{{ language.translate("h3_pedrea") }}</h3>
                                <p>{{ language.translate("text_pedrea") }}</p>

                            </div>
                        </div>
                    </div>
                    <div class="cols">
                        <div class="col8">
                            <div class="box-current-winners">
                                <h1 class="h2 purple">{{ language.translate("prizePool_title") }}</h1>
                                <table id="current-winners" class="table ui-responsive" data-role="table"
                                       data-mode="reflow">
                                    <thead>
                                    <tr>
                                        <th class="td-ball">{{ language.translate("mainprizes_column1") }}</th>
                                        <th class="td-ball">{{ language.translate("mainprizes_column1") }}</th>
                                        <th class="td-star-ball">{{ language.translate("mainprizes_column2") }}</th>
                                        <th class="td-winners">{{ language.translate("mainprizes_column3") }}</th>
                                        <th class="td-prize">{{ language.translate("mainprizes_column4") }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="td-ball">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-ball">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-star-ball">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-winners">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
                                        </td>
                                        <td class="td-prize">
                                            <span>{{ language.translate("mainprizes_row1") }}</span>
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
                        <div class="col8">
                            <div class="content cl">

                                <h3 class="h3">{{ language.translate("h3_otherprizesclaim") }}</h3>
                                <p>{{ language.translate("text_otherprizesclaim") }}</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
{% endblock %}
