var React = require('react');
var EmAddFund = new React.createClass({

    displayName: 'AddFund',

    getInitialState : function ()
    {
        return { show_msg : false, value : null }
    },


    componentDidUpdate : function (prevProps, prevState)
    {
        if(prevState.value != null) {
            this.setState({
                value : null
            });
        }
    },

    handleKeyUp : function (event)
    {
        var pattern = /^\d+(\.\d{1,2})?$/i;
        var b = pattern.test(event.target.value);
        if(!isNaN(event.target.value) && b) {
            this.props.keyup_callback(parseFloat(event.target.value).toFixed(2));
        }
    },

    sanetizedNumValue : function (event)
    {
        var pattern = /^\d{1,2}/i;
        if(pattern.test(event.target.value)) {
            var value = parseFloat(event.target.value).toFixed(2);
            this.setState({ value : value });
        }
    },

    isNumber : function(value) {
        return typeof value === 'number' && isFinite(value) && !isNaN(value)
    },

    render : function ()
    {
        var lbl_msg = (this.state.show_msg) ? ' fdsfdsafdsafd' : '';
        return (
            <div className="box-charge cl">
                <label htmlFor="charge" className="label">Insert an ammount</label>
                <div className="box-combo">
                     <div className="combo currency">{this.props.currency_symbol}</div>
                    <input id="charge" value={this.state.value} className="combo input" value={this.state.value} onBlur={this.sanetizedNumValue} onKeyUp={this.handleKeyUp} type="number" title="This should be a number with up to 2 decimal places."  placeholder='Insert an ammount' />
                </div>
            </div>
        )
    }
});

module.exports = EmAddFund;