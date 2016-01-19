var React = require('react');
var EmAddFund = new React.createClass({

    displayName: 'AddFund',

    handleKeyUp : function (event)
    {
        if(!isNaN(event.target.value)) {
            this.props.keyup_callback(parseFloat(event.target.value).toFixed(2));
        }
    },

    isNumber : function(value) {
        return typeof value === 'number' && isFinite(value) && !isNaN(value)
    },

    render : function ()
    {
        return (
                <div className="box-combo">
                    <div className="combo currency">{this.props.currency_name}</div>
                    <label className="label">Insert an ammount</label><input autoFocus className="combo input" type="number" onKeyUp={this.handleKeyUp} placeholder='Insert an ammount' />
                </div>
        )
    }
});

module.exports = EmAddFund;