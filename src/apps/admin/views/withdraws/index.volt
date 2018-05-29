{% extends "main.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/a/css/pagination.css">
{% endblock %}

{% block bodyClass %}jackpot{% endblock %}

{% block template_scripts %}
    <script>{% include "draws/draws.js" %}</script>
{% endblock %}

{% block meta %}<title>Withdraw - Euromillions Admin System</title>{% endblock %}

{% block header %}
    {% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
    {% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
    <div class="wrapper">
        <div class="container">
            <div class="module">
                <div class="module-body">
                    <h1 class="h1 purple">Manage Withdrawals</h1>
                    <div class="alert alert-success hidden-element">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Changes Saved</strong>
                    </div>
                    <div class="alert alert-danger hidden-element">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Changes Failed</strong>
                    </div>
                    <div class="box-value">
                        <div class="box-draw-data">
                            <div class="cl">
                            </div>
                            <table class="table">
                                <thead>
                                <tr class="special">
                                    <th class="date">Contact detail</th>
                                    <th class="jackpot">Request date</th>
                                    <th class="numbers">Account details</th>
                                    <th class="action">Address</th>
                                    <th class="action">Amt</th>
                                    <th class="action">State</th>
                                    <th class="action">Change State</th>
                                </tr>
                                </thead>
                                <tbody>
                                {% if withdraws is empty %}
                                    <span>No data was found</span>
                                {% else %}
                                    {% for withdraw in withdraws.items %}
                                        <tr>
                                            <td class="date">
                                                {{ withdraw.user.email }}
                                            </td>
                                            <td class="date">
                                                {{ withdraw.date.format('Y-m-d') }}
                                            </td>
                                            <td class="date">
                                                <strong>Name:</strong> {{ withdraw.user.name }}
                                                <br> <strong>Bank Name:</strong> {{ withdraw.user.bankName }}
                                                <br> <strong>IBAN/Acc No:</strong> {{ withdraw.user.bankAccount }}
                                                <br> <strong>BIC/Swift:</strong> {{ withdraw.user.bankSwift }}
                                            </td>
                                            <td class="date">
                                                <strong>Address:</strong> {{ withdraw.user.street }}
                                                <br> <strong>City: </strong>{{ withdraw.user.city }}
                                                <br> <strong>Post Code: </strong> {{ withdraw.user.zip }}
                                                <br> <strong>Country: </strong>{{ withdraw.user.country }}
                                            </td>
                                            <td class="date">
                                                {{ withdraw.movementFormatted }}
                                            </td>
                                            <td class="date">
                                                {{ withdraw.state }}
                                            </td>
                                            <td>
                                                {% if withdraw.state == 'pending' %}
                                                    <a href="/admin/withdraws/confirm" class="btn btn-success">Approved</a>
                                                    <a href="/admin//withdraws/update?id={{ withdraw.id }}&state=rejected" class="btn-danger btn">Rejected</a>
                                                    <a href="/admin//withdraws/update?id={{ withdraw.id }}&state=failed" class="btn btn-primary">Failed</a>
                                                {% else %}
                                                    <a href="/admin/withdraws/update?id={{ withdraw.id }}&state=pending" class="btn btn-primary">Pending</a>
                                                {% endif %}
                                            </td>
                                        </tr>
                                    {% endfor %}
                                {% endif %}
                                </tbody>
                            </table>
                            {{ paginator_view }}
                        </div>
                        <div class="crud-draw hidden-element">
                            <h2 class="sub-title purple"></h2>
                            <form method="post" class="cl form-draw" action="/admin/draws/edit">
                                <div class="cal"></div>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <label for="update-date">Date</label>
                                        <input id="update-date" name="date" class="input" type="text" value="24 Apr 2015">
                                        <label for="update-value">Jackpot Value</label>
                                        <span class="value">&euro;</span> <input id="update-value" name="jackpot" class="input in-value" type="text" value="5.035.400,20">
                                    </div>
                                    <div class="span6">
                                        <label for="update-number">Numbers</label>
                                        <input id="update-number" name="numbers" class="input" type="text" value="02,13,24,32,34">
                                        <label for="update-star-number">Star Numbers</label>
                                        <input id="update-star-number" name="stars" class="input" type="text" value="07,11">
                                    </div>
                                </div>
                                <div class="cl">
                                    <input type="hidden" name="id_draw" id="id_draw" value=""/>
                                    <input type="hidden" name="page" id="page" value="{{ page }}"/>
                                    <a href="javascript:void(0)" class="left btn btn-danger">Cancel</a>
                                    <input type="button" value="Save" class="right btn btn-primary">
                                </div>
                            </form>

                            <h3 class="sub-title purple">Prize breakdown</h3>
                            <table class="table table-breakdown">
                                <thead>
                                <tr class="special">
                                    <th class="match">Match</th>
                                    <th class="prize">Prize</th>
                                    <th class="winners">Winners</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="match"><strong>5+2</strong></td>
                                    <td class="prize"><span class="value">&euro;</span> <input type="text" class="input" value="0"></td>
                                    <td class="winners"><input type="text" class="input" value="0"></td>
                                </tr>
                                <tr>
                                    <td class="match"><strong>5+1</strong></td>
                                    <td class="prize"><span class="value">&euro;</span> <input type="text" class="input" value="0"></td>
                                    <td class="winners"><input type="text" class="input" value="0"></td>
                                </tr>
                                <tr>
                                    <td class="match"><strong>5</strong></td>
                                    <td class="prize"><span class="value">&euro;</span> <input type="text" class="input" value="0"></td>
                                    <td class="winners"><input type="text" class="input" value="0"></td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="cl box-action">
                                <a href="javascript:void(0)" class="left btn btn-danger">Cancel</a>
                                <input type="submit" value="Save" class="right btn btn-primary">
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}