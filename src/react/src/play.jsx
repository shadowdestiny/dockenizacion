var React = require('react');
var ReactDOM = require('react-dom');
var EmCustomizableSelect = require('../components/EmCustomizableSelect.js');
var EuroMillionsLine = require('../components/EmLine.js');



var PlayPage = React.createClass({
    handleCheck: function (event) {
        var active;
        if(event.target.checked) {
            active = true;
        } else {
            active = false;
        }
        this.setState({
            thresholdActive: active
        });
    },

    getInitialState: function () {
        return ({
            thresholdActive: false,
        });
    },

    render: function() {
       return (
               <div>
               <label className="label" htmlFor="threshold">Jackpot Threshold <div data-tip="Set the condition when you want to play or to be informed automatically. Thresholds are calculated only in Euro." className="wrap tipr-normal"><svg className="ico v-question-mark" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-question-mark"</use>'}} /></div></label>
               <div className="box-threshold cl">
                   <input type="checkbox" className="checkbox" id="threshold" onChange={this.handleCheck}/>
                   <EmCustomizableSelect {...this.props} active={this.state.thresholdActive}/>
               </div>
               <div class="test">Test
               </div>
           </div>
       );
    }
});

var default_value = '75';
var default_text = '75 millions €';
var custom_value = 'custom';
var options = [
    {text: '50 millions €', value: '50'},
    {text: default_text, value: default_value},
    {text: '100 millions €', value: '100'},
    {text: 'Choose threshold', value: custom_value}
];

ReactDOM.render(
    <PlayPage options={options} customValue={custom_value} defaultValue={default_value} defaultText={default_text}/>,
    document.getElementById('wrap-threshold')
);

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
    var storage = [];
    for (var i = 0; i <= numberEuroMillionsLine; i++) {
        var selected_numbers = eval('typeof selected_numbers_' + i) != 'undefined' ? eval('selected_numbers_' + i) : {};
        ReactDOM.render(
            <EuroMillionsLine storage={storage} numberPerLine="5" lineNumber={i}/>,
            document.getElementById('num_' + i)
        );
    }
})

