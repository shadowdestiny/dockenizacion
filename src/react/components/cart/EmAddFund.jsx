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

    handleKeyPress : function (event)
    {
        var pattern = /^[0-9\.]+$/;
        var chr = String.fromCharCode(event.which);
        if(!pattern.test(chr)){
            event.preventDefault();
        }
    },

    handleKeyUp : function (event)
    {
        var value_charge = event.target.value;
        //if( value_charge.charAt(0) === '.') {
        //    value_charge = value_charge.substring(0);
        //    console.log(value_charge);
        //}
        var pattern = /^[0-9]+(\.\d{1,2})?$/i;
        var b = pattern.test(value_charge);
        if(!isNaN(value_charge) && b) {
            this.props.keyup_callback(parseFloat(value_charge).toFixed(2));
        } else {
            this.props.keyup_callback(0.00);
        }
    },

    sanetizedNumValue : function (event)
    {
        var pattern = /^\d{1,2}/i;
        if(pattern.test(event.target.value)) {
            var value = parseFloat(event.target.value).toFixed(2);
            this.setState({ value : value });
        } else {
            this.props.keyup_callback(0.00);
            this.setState({ value :  null});
        }
    },

    isNumber : function(value) {
        return typeof value === 'number' && isFinite(value) && !isNaN(value)
    },

    render : function ()
    {
        return (
            <div className="box-charge cl">
                <div className="form-currency">
                    <span className="currency">{this.props.currency_symbol}</span>
                    <input autoFocus id="charge" className="input insert" value={this.state.value} onKeyPress={this.handleKeyPress} onBlur={this.sanetizedNumValue} onKeyUp={this.handleKeyUp} type="text" title="This should be a number with up to 2 decimal places."  placeholder='Insert an amount' />
                </div>
            </div>
        )
    }
});

module.exports = EmAddFund;