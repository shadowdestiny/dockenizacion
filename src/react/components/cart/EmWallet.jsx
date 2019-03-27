var React = require('react');

var EmWallet = new React.createClass({

    displayName: 'Wallet',

    getInitialState : function ()
    {
        return {
            checked     : this.props.show_checked,
            each_price  : this.props.each_price
        }
    },

    handleClick : function (event)
    {

    },

    handleClickLabel : function ()
    {
        if(this.props.moneymatrixiframeloading == true)
        {
            return null;
        }
        var is_checked = this.state.checked;
        var active = is_checked ? false : true;
        this.setState({ checked : active});
        this.props.checked_callback(active);
    },

    handleChange : function (event)
    {
        if(this.props.moneymatrixiframeloading == true)
        {
            return null;
        }
        var active = event.target.checked;
        this.setState({ checked : active});
        this.props.checked_callback(active);
    },

    render : function ()
    {
        var frequency = 1;
        if (this.props.config) {
            frequency = this.props.config.frequency;
        }
        var wallet_balance = (this.state.checked) ? this.props.wallet_balance : 0;
        var total_price = this.props.total_price;
        var style = this.props.moneymatrixiframeloading == true ? '0.3' : '';
        if(this.props.powerplay) {
            total_price = parseFloat(total_price) + ((parseFloat(this.props.total_lines) * parseFloat(this.props.powerplayprice)) * frequency);
        }

        var wallet = (accounting.unformat(wallet_balance) > accounting.unformat(total_price)) ? total_price : wallet_balance;
        var wallet_value = (this.state.checked) ? wallet : 0;

        var disabled_value = (this.state.checked) ? 'summary val' : 'summary val disabled';
        var operand_value = (this.state.checked) ? ' - ' : ' ';
        var value = accounting.formatMoney(wallet_value, this.props.currency_symbol, 2);
        var total_value = this.props.symbol_position ? operand_value + ' ' + value  :  operand_value + ' ' + value;
        return (
            <div className="row cl" style={{paddingLeft:0}}>
                <table className={"order-table-balance"}>
                    <tbody>
                    <tr>
                        <td className={"padding-text"} width="40px">
                            <div className="box-wallet cl disabled" style={{opacity : style}}>
                                <div className="roundedTwo" style={{textAlign:'left'}}>
                                    <input id="pay-wallet" onChange={this.handleChange} type="checkbox" className="checkbox" checked={this.state.checked} />
                                    <label htmlFor="pay-wallet" onClick={this.handleClickLabel} className={this.state.checked ? "checked" : ""}></label>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div className="txt order-text-format" onClick={this.handleClickLabel} style={{opacity : style}}>
                                {this.props.txt_payWithBalanceOption}
                            </div>
                        </td>
                        <td className={"right"}>
                            <div className={disabled_value}>{total_value}</div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        )
    }
});

module.exports = EmWallet;
