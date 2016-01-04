var React = require('react');

var EmWallet = new React.createClass({

    render : function ()
    {
        return (
            <div className="row cl">
                <div className="summary val">&euro; {this.props.wallet_balance}</div>
                <div className="box-wallet cl">
                    <label for="pay-wallet" className="txt">Pay with your Wallet balance</label>
                    <input id="pay-wallet" type="checkbox" className="checkbox" checked />
                </div>
            </div>
        )
    }
});

module.exports = EmWallet;