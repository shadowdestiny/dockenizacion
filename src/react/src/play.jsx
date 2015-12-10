var React = require('react');
var ReactDOM = require('react-dom');
var EuroMillionsLine = require('../components/EmLine.js');
var ThresholdPlay = require('../components/EmThresholdPlay.jsx');
var EuroMillionsBoxAction = require('../components/EmBoxActionPlay.jsx');
var EuroMillionsMultipleEmLines = require('../components/EmMultipleEmLines.jsx');
var EuroMillionsBoxBottomAction = require('../components/EmBoxBottomAction.jsx');
var EmDrawConfig = require('../components/EmDrawConfig.jsx');

var default_value = '75';
var default_text = '75 millions €';
var custom_value = 'custom';
var options = [
    {text: '50 millions €', value: '50'},
    {text: default_text, value: default_value},
    {text: '100 millions €', value: '100'},
    {text: 'Choose threshold', value: custom_value}
];

var options_draw_days = [
    {text: 'Tuesday & Friday' , value : '2,5'},
    {text: 'Tuesday', value : '2'},
    {text: 'Firday' , value : '5'}
];


ReactDOM.render(
    <EuroMillionsBoxBottomAction />,
    document.getElementById('box-bottom-action')
)

ReactDOM.render(
    <ThresholdPlay options={options} customValue={custom_value} defaultValue={default_value} defaultText={default_text}/>,
    document.getElementById('wrap-threshold')
);


ReactDOM.render(
    <EmDrawConfig options={options_draw_days} customValue={custom_value}/>,
    document.getElementById('draw-config')
);


ReactDOM.render(
    <EuroMillionsBoxAction />,
    document.getElementById('box-action')
)

$(function(){
    var numberEuroMillionsLine = 0;
    if(varSize >= 4) { //varSize var is in main.js
        numberEuroMillionsLine = 1;
    }else {
        numberEuroMillionsLine = 5;
    }
    window.onresize = function() {
        if(varSize < 4) {
            if( numberEuroMillionsLine < 5 ) {
                numberEuroMillionsLine = numberEuroMillionsLine + 1;
                ReactDOM.render(
                    <EuroMillionsLine numberPerLine="5" lineNumber={numberEuroMillionsLine}/>,
                    document.getElementById('num_' + numberEuroMillionsLine)
                );
            }
        }
    }

    ReactDOM.render(
        <EuroMillionsMultipleEmLines numberEuroMillionsLine={numberEuroMillionsLine}/>
        ,
        document.getElementById('box-lines')
    );

})



