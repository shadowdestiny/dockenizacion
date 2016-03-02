var React = require('react');
var EmCustomizableSelect = require('../components/EmCustomizableSelect.jsx');
var ReactTooltip = require("react-tooltip");

var PlayPage = React.createClass({
    handleCheck: function (event) {
        var active;
        active = event.target.checked;
        this.setState({
            thresholdActive: active
        });
    },

    getInitialState: function(){
        return ({
            thresholdActive: false,
        });
    },

    render: function(){
        var react_tooltip = <ReactTooltip type="light" id='threshold-tip'/>;
        return (
            <div id="wrap-threshold" className="col6 wrap-threshold">
                <label className="label" htmlFor="threshold">Jackpot Threshold<div data-for="threshold-tip" data-tip="Set the condition when you want to play or to be informed automatically. Thresholds are calculated only in Euro." className="wrap"><svg className="ico v-question-mark"><use xlinkHref="/w/svg/icon.svg#v-question-mark"></use></svg></div>
                </label>
                <div className="box-threshold cl">
                    <input type="checkbox" className="checkbox" id="threshold" onChange={this.handleCheck}/>
                    <EmCustomizableSelect {...this.props} active={this.state.thresholdActive}/>
                </div>
                {react_tooltip}
            </div>
        );
    }
});


module.exports = PlayPage;