var React = require('react');

var EmWallet = new React.createClass({

    displayName: 'Wallet',

    propTypes: {
        wallet_balance : React.PropTypes.number.isRequired
    },

    getInitialState : function ()
    {
        return {
            checked : true
        }
    },

    onChangeChecked : function ()
    {
        var checked_state = (this.state.checked) ? false : true;
        this.setState({ checked : checked_state});
        this.props.checked_callback(checked_state);

    },

    render : function ()
    {
        var wallet_balance = (this.state.checked) ? parseFloat(this.props.wallet_balance) : 0;
        var total_price = parseFloat(this.props.total_price);
        var wallet = (wallet_balance > total_price) ? total_price : wallet_balance;
        var wallet_value = (this.state.checked) ? wallet : 0;
        var disabled_value = (this.state.checked) ? 'summary val' : 'summary val disabled';
        var operand_value = (this.state.checked) ? ' - ' : ' ';
        var total_value = this.props.currency_symbol + operand_value +  wallet_value.toFixed(2);

        return (
            <div className="row cl">
                <div className={disabled_value}>{total_value}</div>
                <div className="box-wallet cl">
                    <label htmlFor="pay-wallet" className="txt">Pay with your Wallet balance</label>
                    <input id="pay-wallet" onChange={this.onChangeChecked} type="checkbox" className="checkbox" checked={this.state.checked} />
                </div>
            </div>
        )
    }
});

module.exports = EmWallet;