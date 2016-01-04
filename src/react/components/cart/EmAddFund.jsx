var React = require('react');

var EmAddFund = new React.createClass({


    handleKeyUp : function (event)
    {
        this.props.keyup_callback(event.target.value);
    },

    render : function ()
    {
        return (
                <div className="box-combo">
                    <div className="combo currency">&euro;</div>
                    <input className="combo input" onKeyUp={this.handleKeyUp} type="text" placeholder='Insert an ammount' />
                </div>
        )
    }
});

module.exports = EmAddFund;