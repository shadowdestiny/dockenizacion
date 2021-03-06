var React = require('react');
import EmCustomizableSelect from '../components/EmCustomizableSelect.jsx';
import ReactTooltip from "react-tooltip";
var createReactClass = require('create-react-class');
var PlayPage = createReactClass({

    getInitialState: function(){
        return ({
            thresholdActive: false,
        });
    },

    componentWillReceiveProps : function(nextProps) {
      if(nextProps.active) {
          this.setState( { thresholdActive : false } );
      }
    },

    handleCheck: function (event) {
        var active;
        active = event.target.checked;
        this.setState({
            thresholdActive: active
        });
        this.props.callback_threshold(active);
    },

    handleClickLabel : function ()
    {
        var is_checked = this.state.thresholdActive;
        var active = is_checked ? false : true;
        this.setState({ thresholdActive : active});
        this.props.callback_threshold(active);
    },

    render: function(){
        var react_tooltip = <ReactTooltip type="light" id='threshold-tip'/>;
        return (
            <div id="wrap-threshold" className="col6 wrap-threshold">
                <label className="label" htmlFor="threshold">Jackpot Threshold<div data-for="threshold-tip" data-tip="Set the condition when you want to play or to be informed automatically. Thresholds are calculated only in Euro." className="wrap"><svg className="ico v-question-mark"><use xlinkHref="/w/svg/icon.svg#v-question-mark"></use></svg></div>
                </label>
                <div className="box-threshold cl">
                    <input type="checkbox" className="checkbox" id="threshold" checked={this.state.thresholdActive} onChange={this.handleCheck}/>
                    <EmCustomizableSelect {...this.props} label_callback={this.handleClickLabel} active={this.state.thresholdActive}/>
                </div>
                {react_tooltip}
            </div>
        );
    }
});


export default  PlayPage;