var React = require('react');

var EmWallet = new React.createClass({

    displayName: 'Wallet',

    getInitialState : function ()
    {
        return {
            checked : true
        }
    },

    handleClick : function (event)
    {

    },

    handleClickLabel : function (e)
    {
        var is_checked = this.state.checked;
        var active = is_checked ? false : true;
        this.setState({ checked : active});
        this.props.checked_callback(active);

    },

    handleChange : function (event)
    {
        var active;
        if(event.target.checked) {
            active = true;
        } else {
            active = false;
        }
        this.setState({ checked : active});
        this.props.checked_callback(active);
    },

    render : function ()
    {
        var wallet_balance = (this.state.checked) ? parseFloat(this.props.wallet_balance) : 0;
        var total_price = parseFloat(this.props.total_price);
        var wallet = (wallet_balance > total_price) ? total_price : wallet_balance;
        var wallet_value = (this.state.checked) ? wallet : 0;
        var disabled_value = (this.state.checked) ? 'summary val' : 'summary val disabled';
        var operand_value = (this.state.checked) ? ' - ' : ' ';
        var total_value = this.props.symbol_position ? operand_value + ' ' + wallet_value.toFixed(2) + ' ' + this.props.currency_symbol : this.props.currency_symbol  + ' ' + operand_value + ' ' + wallet_value.toFixed(2);

        return (
            <div className="row cl">
                <div className={disabled_value}>{total_value}</div>
                <div className="box-wallet cl">
                    <label onClick={this.handleClickLabel} className="txt">Pay with your Wallet balance</label>
                    <input id="pay-wallet" onChange={this.handleChange} type="checkbox" className="checkbox" checked={this.state.checked} />
                </div>
            </div>
        )
    }
});

module.exports = EmWallet;