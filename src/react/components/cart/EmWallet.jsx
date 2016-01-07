var React = require('react');

var EmWallet = new React.createClass({


    getInitialState : function ()
    {
        return {
            checked : true
        }
    },

    onChangeChecked : function (event)
    {
        var checked_state = (this.state.checked) ? false : true;
        this.setState({ checked : checked_state});
        this.props.checked_callback(checked_state);

    },

    render : function ()
    {
        var wallet_balance = parseFloat(this.props.wallet_balance);
        var total_price = parseFloat(this.props.total_price);
        var wallet = wallet_balance > total_price ? total_price : wallet_balance;
        return (
            <div className="row cl">
                <div className="summary val">{this.props.currency_symbol} - {wallet}</div>
                <div className="box-wallet cl">
                    <label htmlFor="pay-wallet" className="txt">Pay with your Wallet balance</label>
                    <input id="pay-wallet" onChange={this.onChangeChecked} type="checkbox" className="checkbox" checked={this.state.checked} />
                </div>
            </div>
        )
    }
});

module.exports = EmWallet;