{% extends "main.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/a/css/pagination.css">
{% endblock %}

{% block bodyClass %}jackpot{% endblock %}

{% block template_scripts %}
    <script>{% include "draws/draws.js" %}</script>
{% endblock %}

{% block meta %}<title>Jackpot - Euromillions Admin System</title>{% endblock %}

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
                <h1 class="h1 purple">Jackpot</h1>
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
                        <div class="left">
                            <label for="search">Search by date</label>
                            <input id="search" class="input" type="text">
                            <a href="javascript:void(0)" class="btn btn-primary search right add">Search</a>
                        </div>
                        <a href="javasscript:void(0);" class="btn btn-primary right add">Add New</a>
                        </form>
                        <table class="table">
                            <thead>
                                <tr class="special">
                                    <th class="date">Date</th>
                                    <th class="jackpot">Jackpot</th>
                                    <th class="numbers">Numbers</th>
                                    <th class="action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            {% if draws is empty %}
                                <span>No data was found</span>
                            {% else %}
                            {% for draw in draws %}
                                <tr>
                                    <td class="date">
                                        {{ draw.draw_date }}
                                    </td>
                                    <td class="jackpot">
                                        &euro; {{ draw.jackpot | number_format(0, ',', '.')}}
                                    </td>
                                    <td class="numbers">
                                        {% for number in draw.regular_numbers %}
                                            <span class="num">{{ number }}</span>
                                        {% endfor %}
                                        {% for lucky in draw.lucky_numbers %}
                                            <span class="num yellow">{{ lucky }}</span>
                                        {% endfor %}
                                    </td>
                                    <td class="action">
                                         <a href="javascript:void(0)" data-id="{{ draw.id }}" class="btn btn-primary">Edit</a>
                                    </td>
                                </tr>
                            {% endfor %}
                            {% endif %}
                            </tbody>
                        </table>
                          {% include "_elements/pagination.volt" %}
                    </div>
                    <div class="crud-draw hidden-element">
                        <h2 class="sub-title purple"></h2>
                        <form method="post" class="cl form-draw" action="/admin/draws/edit">
                            <div class="cal">
                            </div>
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