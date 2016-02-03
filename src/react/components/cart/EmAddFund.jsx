var React = require('react');
var EmAddFund = new React.createClass({

    displayName: 'AddFund',

    getInitialState : function ()
    {
        return { show_msg : false, value : null, active : false }
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
        var code = event.keyCode || event.which;
        var pattern = /^[0-9]+(\.\d{1,2})?$/i;
        var b = pattern.test(value_charge);
        if(!isNaN(value_charge) && b )  {
                this.props.keyup_callback(parseFloat(value_charge).toFixed(2));
        } else {
                this.props.keyup_callback(0.00);
        }
    },

    updateValue: function(e) {
        e.preventDefault();
        this.setState({
            value: e.target.value
        });
    },

    handleClick : function(e)
    {
       this.setState({
           active : false
       })
    },

    handleBlur : function (event)
    {
        this.setState( {
            active : true
        });
    },

    handleFocus : function (e) {
        e.preventDefault();
        if(e.target.value != "") {
            this.setState({
                value: parseFloat(e.target.value).toFixed(2)
            });

        }
    },

    isNumber : function(value) {
        return typeof value === 'number' && isFinite(value) && !isNaN(value)
    },

    render : function ()
    {
        var _value = this.state.value;
        if(this.state.active) {
            var pattern = /^\d/i;
            _value = (pattern.test(_value)) ? parseFloat(_value).toFixed(2) : "";
        }
        return (
            <div className="box-charge cl">
                <div className="form-currency">
                    <span className="currency">{this.props.currency_symbol}</span>
                    <input autoFocus id="charge"  className="input insert"  value={_value}
                           onKeyPress={this.handleKeyPress}
                           onClick={this.handleClick}
                           onChange={this.updateValue}
                           onBlur={this.handleBlur}
                           onFocus={this.handleFocus}
                           onKeyUp={this.handleKeyUp}
                           type="text" title="This should be a number with up to 2 decimal places."  placeholder='Insert an amount' />
                </div>
            </div>
        )
    }
});

module.exports = EmAddFund;