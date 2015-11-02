{% extends "main.volt" %}

{% block template_scripts %}
<script src="/a/js/chart.min.js"></script>
<script src="/a/js/chart.HorizontalBar.min.js"></script>
<script src="/a/js/data.js"></script>
{% endblock %}

{% block bodyClass %}business{% endblock %}

{% block meta %}<title>Business statistics - Euromillions Admin System</title>{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
<div class="wrapper">
    <div class="container">
        <h1 class="h1 purple">Business Statistics</h1>

        *BE CAREFUL TO WATCH OUT FOR THE CONFIGURATIONS OF CHART.JS and Horizontal bar - I made different examples to help you with the personalization of the data based of which graph is needed*
        <div class="row">
            <div class="span6">
                <div class="content">
                   <div class="module box-chart">
                        <div class="module-head cl">
                            <h3 class="title">TICKET SOLD</h3>
                            <select class="select">
                                <option>Tickets</option>
                                <option>Plays</option>
                                <option selected="selected">Plays &amp; Tickets</option>
                            </select>
                        </div>
                        <div class="module-body">
                            <div class="chart">
                                <div class="option cl">
                                    <select class="select">
                                        <option>2015</option>
                                        <option>2014</option>
                                        <option>2013</option>
                                        <option>2012</option>
                                    </select>
                                    <ul class="cl">
                                        <li class="day">Day</li>
                                        <li class="week">Week</li>
                                        <li class="month">Month</li>
                                        <li class="active year">Year</li>
                                        <li class="all">All</li>
                                    </ul>
                                </div>
                                <canvas id="myChart01" class="canvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/.content-->
            </div>
            <div class="span6">
                <div class="content">
                   <div class="module box-chart">
                        <div class="module-head cl">
                            <h3 class="title">Average Sold</h3>
                            <select class="select">
                                <option>Tickets</option>
                                <option>Plays</option>
                                <option selected="selected">Plays &amp; Tickets</option>
                            </select>
                        </div>
                        <div class="module-body">
                            <div class="chart">
                                <div class="option cl">
                                    <ul class="cl">
                                        <li class="week active">Week</li>
                                        <li class="month">Month</li>
                                    </ul>
                                </div>
                                <canvas id="myChart04" class="canvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="span6">
                <div class="content">
                   <div class="module box-chart">
                        <div class="module-head cl">
                            <h3 class="title">Income (Added funds)</h3>
                            <select class="select">
                                <option>Total</option>
                                <option>Only Single Tickets</option>
                                <option>Only Multiple Tickets</option>
                            </select>
                        </div>
                        <div class="module-body">
                            <div class="chart">
                                <div class="option cl">
                                    <select class="select">
                                        <option>January</option>
                                        <option>February</option>
                                        <option>March</option>
                                        <option>April</option>
                                        <option>May</option>
                                        <option>June</option>
                                        <option>July</option>
                                        <option>August</option>
                                        <option>September</option>
                                        <option>October</option>
                                        <option>November</option>
                                        <option>December</option>
                                    </select>
                                    <ul class="cl">
                                        <li class="day">Day</li>
                                        <li class="week">Week</li>
                                        <li class="month active">Month</li>
                                        <li class="year">Year</li>
                                        <li class="all">All</li>
                                    </ul>
                                </div>
                                <canvas id="myChart02" class="canvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="content">
                   <div class="module box-chart">
                        <div class="module-head cl">
                            <h3 class="title">Winnings (Outgo)</h3>
                        </div>
                        <div class="module-body">
                            <div class="chart">
                                <div class="option cl">
                                    <select class="select">
                                        <option>January</option>
                                        <option>February</option>
                                        <option>March</option>
                                        <option>April</option>
                                        <option>May</option>
                                        <option>June</option>
                                        <option>July</option>
                                        <option>August</option>
                                        <option>September</option>
                                        <option>October</option>
                                        <option>November</option>
                                        <option>December</option>
                                    </select>
                                    <ul class="cl">
                                        <li class="day">Day</li>
                                        <li class="week">Week</li>
                                        <li class="month active">Month</li>
                                        <li class="year">Year</li>
                                        <li class="all">All</li>
                                    </ul>
                                </div>
                                <canvas id="myChart03" class="canvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/.content-->
            </div>
        </div>


        <div class="row">
            <div class="span12">
                <div class="content">
                   <div class="module box-chart">
                        <div class="module-head cl">
                            <h3 class="title">Jackpot Interest</h3>
                            <select class="select">
                                <option>Total</option>
                                <option>Only Single Tickets</option>
                                <option>Only Multiple Tickets</option>
                            </select>
                        </div>
                        <div class="module-body">
                            <div class="chart">
                                <div class="option cl">
                                    <ul class="cl">
                                        <li class="day">Day</li>
                                        <li class="week">Week</li>
                                        <li class="month">Month</li>
                                        <li class="year">Year</li>
                                        <li class="all active">All</li>
                                    </ul>
                                </div>
                                <canvas id="myChart05" class="canvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="span6">
               <div class="module box-chart">
                    <div class="module-head cl">
                        <h3 class="title">Ticket Played</h3>
                     </div>
                    <div class="module-body">
                        <div class="chart">
                            <div class="option cl">
                                <ul class="cl">
                                    <li class="day">Day</li>
                                    <li class="week">Week</li>
                                    <li class="month">Month</li>
                                    <li class="year">Year</li>
                                    <li class="all active">All</li>
                                </ul>
                            </div>
                            <canvas id="myChart06" class="canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="module box-chart">
                    <div class="module-head cl">
                        <h3 class="title">Players Activity</h3>
                        <select class="select">
                            <option>New Sign up</option>
                            <option>New Players</option>
                            <option selected="selected">Active Players</option>
                            <option>Total Players</option>
                        </select>
                        <select class="select">
                            <option>Last Year</option>
                            <option>Last Month</option>
                            <option selected="selected">Last week</option>
                            <option>Select range of time</option>
                        </select>
                    </div>
                    <div class="module-body">
                        <div class="chart">
                            <div class="option cl">
                                <select class="select">
                                    <option>2015</option>
                                    <option>2014</option>
                                    <option>2013</option>
                                    <option>2012</option>
                                </select>
                                <ul class="cl">
                                    <li class="day">Day</li>
                                    <li class="week">Week</li>
                                    <li class="month">Month</li>
                                    <li class="year active">Year</li>
                                    <li class="all">All</li>
                                </ul>
                            </div>
                            <canvas id="myChart07" class="canvas"></canvas>
                        </div>
                    </div>
                </div>
            
                *new register*
                *new players*
                *active players* (define period of time)
                *total players*
            </div>
        </div>

        <div class="legend">
            <h3>Legend</h3>
            <p><strong>Play:</strong> It is a single or multiple tickets played by the same player in a single buying process.</p>
            <p><strong>Tickets:</strong> It is a single ticket played</p>
        </div>

    </div>
</div>
{% endblock %}