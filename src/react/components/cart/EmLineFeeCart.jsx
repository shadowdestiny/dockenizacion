var React = require('react');
var EmAddFund = require('./EmAddFund.jsx');

var EmLineFeeCart = new React.createClass({

    displayName: 'EmLineFeeCart',

    getInitialState : function ()
    {
        return {
            show_addfund: false
        };

    },

    handleClick : function ()
    {
        var show_add_fund = this.state.show_addfund;
        if(show_add_fund) {
            this.setState( {show_addfund : false} );
        } else {
            this.setState( {show_addfund : true} );
        }
        this.props.callbackHandleClickAdd(show_add_fund);

    },


    render : function ()
    {
        var addFundComponent = null;
        var price_and_symbol_fee_below_text = this.props.symbol_position ? this.props.price_below_fee + ' ' + this.props.currency_symbol : this.props.currency_symbol + ' ' + this.props.price_below_fee;
        if(this.state.show_addfund) {
            addFundComponent = <EmAddFund currency_symbol={this.props.currency_symbol} keyup_callback={this.props.keyup} show={this.state.show_addfund}/>;
        }

        if(!this.props.show_all_fee){
            return null;
        }
        var show_fee_text = 'No extra fee';
        if(this.props.show_fee_text) {
            /*show_fee_text = 'Fee for transactions below ' + price_and_symbol_fee_below_text;*/
            show_fee_text = txt_checkout_fee;
        }

        var fee_value = this.props.symbol_position ? + this.props.fee_charge : this.props.currency_symbol + '' + this.props.fee_charge;
        if (this.props.symbol_position){
            fee_value = this.props.currency_symbol +""+ fee_value;
        }
        if(!this.props.show_fee_value) {
            fee_value = '';
        }
        return (
            <div className="row cl" style={{paddingTop:"10px"}}>
                <table className={"order-table-balance"}>
                    <tbody>
                    <tr>
                        <td>
                            <div className="txt-fee">
                                <span style={{textTransform:'none'}} className={"order-text-format font-base"}>{show_fee_text}</span>
                            </div>
                        </td>
                        <td>
                            <div className="summary val">{fee_value}</div>
                        </td>
                    </tr>
                    </tbody>
                </table>


                {/* <div className="box-funds cl">
                    <a className="add-funds" onClick={this.handleClick} href="javascript:void(0)">Add Funds to avoid charges</a>
                    {addFundComponent}
                </div>*/}
            </div>
        )
    }
});

module.exports = EmLineFeeCart;