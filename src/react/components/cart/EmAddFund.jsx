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
        var pattern = /^[0-9]+$/;
        var chr = String.fromCharCode(event.which);
        if(!pattern.test(chr)){
            event.preventDefault();
        }
    },

    handleKeyUp : function (event)
    {
        //var charge_value = document.getElementById('charge').value;
        //var pattern = /^[0-9.]+$/;
        //if(!pattern.test(charge_value)) {
        //    event.preventDefault();
        //}
        var pattern = /[0-9]+(\.\d{1,2})?$/i;
        var b = pattern.test(event.target.value);
        if(!isNaN(event.target.value) && b) {
            this.props.keyup_callback(parseFloat(event.target.value).toFixed(2));
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