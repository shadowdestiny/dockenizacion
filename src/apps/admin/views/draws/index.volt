{% extends "main.volt" %}

{% block template_css %}
    <link rel="stylesheet" href="/a/css/calendar.css">
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
                <div class="box-value">
                    <div class="cl">
                        <form class="search left cl">
                            <input class="input" type="text" placeholder="search by date">
                            <input class="btn btn-primary" type="button" value="Search">
                        </form>
                        <a href="javascript:void(0)" class="right btn btn-primary add">Add new</a>
                    </div>
                    <table class="table">
                        <thead>
                            <tr class="special">
                                <th class="date">Date</th>
                                <th class="jackpot">Jackpot</th>
                                <th class="numbers">Numbers</th>
                                <th class="breakdown">Prize Breakdown</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% if draws is empty %}
                                <tr>
                                    <td colspan="5">No data was found</td>
                                </tr>
                            {% else %}
                                {% for draw in draws %}
                                    <tr>
                                        <td class="date">
                                            {{ draw.draw_date }}
                                        </td>
                                        <td class="jackpot">
                                            &euro; {{ draw.jackpot }}
                                        </td>
                                        <td class="numbers">
                                            {% for number in draw.regular_numbers %}
                                                <span class="num">{{ number }}</span>
                                            {% endfor %}
                                            {% for lucky in draw.lucky_numbers %}
                                                <span class="num yellow">{{ lucky }}</span>
                                            {% endfor %}
                                        </td>
                                        <td>
                                            * Defined / Not Defined *
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

                    <h2 class="sub-title purple">/* Add / Edit */ Draws</h3>
                    <form class="cl update">
                        <div class="cal">
                          <table class="cal-table">
                            <caption class="cal-caption">
                              <a class="prev" href="index.html">«</a>
                              <a class="next" href="index.html">»</a>
                              May 2012
                            </caption>
                            <tbody class="cal-body">
                              <tr>
                                <td class="cal-off"><a href="index.html">30</a></td>
                                <td><a href="index.html">1</a></td>
                                <td><a href="index.html">2</a></td>
                                <td class="cal-today"><a href="index.html">3</a></td>
                                <td><a href="index.html">4</a></td>
                                <td><a href="index.html">5</a></td>
                                <td><a href="index.html">6</a></td>
                              </tr>
                              <tr>
                                <td><a href="index.html">7</a></td>
                                <td class="cal-selected"><a href="index.html">8</a></td>
                                <td><a href="index.html">9</a></td>
                                <td><a href="index.html">10</a></td>
                                <td><a href="index.html">11</a></td>
                                <td class="cal-check"><a href="index.html">12</a></td>
                                <td><a href="index.html">13</a></td>
                              </tr>
                              <tr>
                                <td><a href="index.html">14</a></td>
                                <td><a href="index.html">15</a></td>
                                <td><a href="index.html">16</a></td>
                                <td class="cal-check"><a href="index.html">17</a></td>
                                <td><a href="index.html">18</a></td>
                                <td><a href="index.html">19</a></td>
                                <td><a href="index.html">20</a></td>
                              </tr>
                              <tr>
                                <td><a href="index.html">21</a></td>
                                <td><a href="index.html">22</a></td>
                                <td><a href="index.html">23</a></td>
                                <td><a href="index.html">24</a></td>
                                <td><a href="index.html">25</a></td>
                                <td><a href="index.html">26</a></td>
                                <td><a href="index.html">27</a></td>
                              </tr>
                              <tr>
                                <td><a href="index.html">28</a></td>
                                <td><a href="index.html">29</a></td>
                                <td><a href="index.html">30</a></td>
                                <td><a href="index.html">31</a></td>
                                <td class="cal-off"><a href="index.html">1</a></td>
                                <td class="cal-off"><a href="index.html">2</a></td>
                                <td class="cal-off"><a href="index.html">3</a></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>

                        <div class="row-fluid">
                            <div class="span6">
                                <label for="update-date">Date</label>
                                <input id="update-date" class="input" type="text" value="24 Apr 2015">
                                <label for="update-value">Jackpot Value</label>
                                <span class="value">&euro;</span> <input id="update-value" class="input in-value" type="text" value="5.035.400,20">
                            </div>
                            <div class="span6">
                                <label for="update-number">Numbers</label>
                                <input id="update-number" class="input" type="text" value="02,13,24,32,34">
                                <label for="update-star-number">Star Numbers</label>
                                <input id="update-star-number" class="input" type="text" value="07,11">
                            </div>
                        </div>

                        <h3 class="sub-title purple">Prize breakdown</h3>
                        <table class="table">
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
{% endblock %}