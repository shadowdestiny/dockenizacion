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
            <div className="box-charge cl">
                <label htmlFor="charge" className="label">Insert an ammount</label>
                <div className="box-combo">
                     <div className="combo currency">{this.props.currency_symbol}</div>
                    <input id="charge" className="combo input" onKeyUp={this.handleKeyUp} type="text" placeholder='Insert an ammount' />
                </div>
            </div>
        )
    }
});

module.exports = EmAddFund;