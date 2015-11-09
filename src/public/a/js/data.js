Chart.defaults.global.responsive = true;
Chart.defaults.global.animation = false;
Chart.defaults.global.multiTooltipTemplate = "<%= datasetLabel %>: <%= value %>";

var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
var randomScalingFactor2 = function(){ return Math.round(Math.random()*1000)};

var barChartData1 = {
    labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
    datasets:[
        {   
            label:"Red bar",
            fillColor:"rgba(255,76,76,1)",
            data: [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
        },
        {
            label:"Blue bar",
            fillColor:"rgba(76,148,219,1)",
            data:[randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
        }
    ]
}

var barChartData2 = {
    labels: ["","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"],
    datasets:[
        {
            label:"Date",
            fillColor:"rgba(33,120,33,.5)",
            strokeColor:"rgba(33,120,33,1)",
            pointColor:"#040",
            data:[randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2()]
        }
    ]
}

var barChartData3 = {
    labels: ["","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"],
    datasets:[
        {
            label:"Date",
            fillColor:"rgba(76,148,219,.5)",
            strokeColor:"rgba(76,148,219,1)",
            pointColor:"#036",
            data:[randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2()]
        }
    ]
}

var barChartData4 = {
    labels: ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"],
    datasets:[
        {   
            fillColor:"rgba(76,148,219,1)",
            data: [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
        }
    ]
}

var barChartData5 = {
    labels: ["Sep 2014","Oct 2014","Nov 2014","Dec 2014","Jan 2015","Feb 2015 ","Mar 2015","Apr 2015","May 2015","Jun 2015","Jul 2015","Aug 2015 ","Sep 2015","Oct 2015","Nov 2015","Dec 2015"],
    datasets:[
        {
            label:"Jackpot",
            fillColor:"transparent",
            strokeColor:"rgba(255,76,76,1)",
            pointColor:"#900",
            data: [randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2(),randomScalingFactor2()]
        },{   
            label:"Added funds",
            fillColor:"transparent",
            strokeColor:"rgba(33,120,33,1)",
            pointColor:"#040",
            data: [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
        }
    ]
}

var barChartData6 = [
    {
        label:"Single ticket",
        value: 200,
        color:"#F7464A"
    },{
        label:"2 tickets",
        value: 100,
        color: "#46BFBD"
    },{
        label:"3 tickets",
        value: 75,
        label:"3 tickets",
        color: "#FDB45C"
    },{
        label:"4 tickets",
        value: 40,
        label:"4 tickets",
        color: "#069"
    },{
        label:"5 tickets",
        value: 25,
        label:"5 tickets",
        color: "#393"
    }
]

var barChartData7 = {
    labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
    datasets:[
        {
            label:"Active Players",
            fillColor:"transparent",
            strokeColor:"rgba(76,148,219,1)",
            pointColor:"#59c",
            data:[randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
        }
    ]
}

$(function(){
    // Get context with jQuery - using jQuery's .get() method.
    var ctx1 = $("#myChart01").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var myNewChart1 = new Chart(ctx1).Bar(barChartData1);

    var ctx2 = $("#myChart02").get(0).getContext("2d");
    var myNewChart2 = new Chart(ctx2).Line(barChartData2,{
        bezierCurve: false,
        scaleLabel: "<%= Number(value).toFixed(2).replace('.', ',') + ' €'%>",
        tooltipTemplate: "<%= value + ' €' %>"
    });

    var ctx3 = $("#myChart03").get(0).getContext("2d");
    var myNewChart3 = new Chart(ctx3).Line(barChartData3,{
        bezierCurve: false,
        scaleLabel: "<%= Number(value) + ' €'%>",
        tooltipTemplate: "<%= value + ' €' %>"
    });

    var ctx4 = $("#myChart04").get(0).getContext("2d");
    var myNewChart4 = new Chart(ctx4).HorizontalBar(barChartData4);

    var ctx5 = $("#myChart05").get(0).getContext("2d");
    var myNewChart5 = new Chart(ctx5).Line(barChartData5,{
        bezierCurve: false
    });

    var helpers = Chart.helpers;
    var ctx6 = $("#myChart06").get(0).getContext("2d");
    var myNewChart6 = new Chart(ctx6).Pie(barChartData6,{
        tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>"
    });
    
    /* DRAW LEGEND */
    var legendHolder = document.createElement('div');
    legendHolder.innerHTML = myNewChart6.generateLegend();

    // Include a html legend template after the module doughnut/pie itself
    helpers.each(legendHolder.firstChild.childNodes, function(legendNode, index){
        helpers.addEvent(legendNode, 'mouseover', function(){
            var activeSegment = myNewChart6.segments[index];
            activeSegment.save();
            myNewChart6.showTooltip([activeSegment]);
            activeSegment.restore();
        });
    });
    helpers.addEvent(legendHolder.firstChild, 'mouseout', function () {
        myNewChart6.draw();
    });
    myNewChart6.chart.canvas.parentNode.parentNode.appendChild(legendHolder.firstChild);

    var ctx7 = $("#myChart07").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var myNewChart7 = new Chart(ctx7).Line(barChartData7,{
        bezierCurve: false
    });
});