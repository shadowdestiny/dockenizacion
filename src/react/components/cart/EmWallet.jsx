var React = require('react');

var EmWallet = new React.createClass({

    displayName: 'Wallet',

    getInitialState : function ()
    {
        return {
            checked : this.props.show_checked
        }
    },

    handleClick : function (event)
    {

    },

    handleClickLabel : function ()
    {
        var is_checked = this.state.checked;
        var active = is_checked ? false : true;
        this.setState({ checked : active});
        this.props.checked_callback(active);
    },

    handleChange : function (event)
    {
        var active = event.target.checked;
        this.setState({ checked : active});
        this.props.checked_callback(active);
    },

    render : function ()
    {
        var wallet_balance = (this.state.checked) ? this.props.wallet_balance : 0;
        var total_price = this.props.total_price;
        var wallet = (accounting.unformat(wallet_balance) > accounting.unformat(total_price)) ? total_price : wallet_balance;
        var wallet_value = (this.state.checked) ? wallet : 0;
        var disabled_value = (this.state.checked) ? 'summary val' : 'summary val disabled';
        var operand_value = (this.state.checked) ? ' - ' : ' ';

        var value = accounting.formatMoney(wallet_value, this.props.currency_symbol, 2);
        var total_value = this.props.symbol_position ? operand_value + ' ' + value  :  operand_value + ' ' + value;
        //<div className="balance">
        //    <span className="txt">Balance:</span>
        //    {this.props.old_new_balance}
        //</div>

        return (
            <div className="row cl">
                <div className={disabled_value}>{total_value}</div>
                <div className="box-wallet cl">
                    <label onClick={this.handleClickLabel} className="txt">Pay with your Account balance</label>
                    <input id="pay-wallet" onChange={this.handleChange} type="checkbox" className="checkbox" checked={this.state.checked} />
                </div>
            </div>
        )
    }
});

module.exports = EmWallet;