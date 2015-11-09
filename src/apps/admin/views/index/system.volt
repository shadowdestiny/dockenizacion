{% extends "main.volt" %}

{% block template_scripts %}
<script src="/a/js/chart.min.js"></script>
<script src="/a/js/chart.HorizontalBar.min.js"></script>
<script>
    Chart.defaults.global.responsive = true;
    Chart.defaults.global.animation = false;
    Chart.defaults.global.multiTooltipTemplate = "<%= datasetLabel %>: <%= value %>";

    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

    var barChartData1 = {
        labels: ["07 Feb 2015","21 Feb 2015","8 Mar 2015","22 Mar 2015"," 7 Apr 2015"],
        datasets:[
            {
                fillColor:"rgba(76,148,219,.5)",
                strokeColor:"rgba(76,148,219,1)",
                pointColor:"#036",
                data:[randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
            }
        ]
    }

    $(function(){
        // Get context with jQuery - using jQuery's .get() method.
        var ctx1 = $("#myChart01").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var myNewChart1 = new Chart(ctx1).Line(barChartData1,{
            bezierCurve: false
        });
    });
</script>
{% endblock %}

{% block bodyClass %}system{% endblock %}

{% block meta %}<title>System statistics - Euromillions Admin System</title>{% endblock %}

{% block header %}
{% set activeNav='{"myClass": ""}'|json_decode %} {# It need to be empty #}
{% include "_elements/header.volt" %}
{% endblock %}

{% block footer %}{% include "_elements/footer.volt" %}{% endblock %}

{% block body %}
 <div class="wrapper">
    <div class="container">
        <h1 class="h1 purple">System Statistics</h1>
        <div class="row">
            <div class="span6">
                <div class="content">
                   <div class="module box-chart">
                        <div class="module-head cl">
                            <h3 class="title">Average loading time</h3>
                            <select class="select">
                                <option>Home</option>
                                <option>Play</option>
                                <option>Numbers</option>
                                <option>Faq</option>
                                <option>Cart</option>
                                <option>Sign in</option>
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
            </div>
        </div>
    </div>
</div>
{% endblock %}