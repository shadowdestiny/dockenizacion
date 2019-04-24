var React = require('react');
var ReactDOM = require('react-dom');


var EmTotalCart = new React.createClass({

    displayName: 'EmTotalCart',


    componentWillReceiveProps : function(newProps) {
        $(document).trigger("totalPriceEvent", [ newProps.pricetopay, newProps.funds ]);
    },

    render : function ()
    {
        return (
            <div className="row box-total cl order-text-format order-separator-container">
                <table className={"order-table-balance"}>
                    <tbody>
                    <tr>
                        <td className={"order-text-format"}>
                            <span className={"summary text order-text-format title-base"} >
                                {this.props.txtMultTotalPrice}
                            </span>
                        </td>
                        <td className={"order-text-format right"}>
                            <span className={"summary val title-base"} style={{fontWeight:"bold",textAlign:"right"}}>
                                {this.props.total_price }
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td >&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <div className="txt-currency base-format desktop">
                            <span className={"summary text order-text-format title-base-2"}>
                                {this.props.txt_currencyAlert}
                            </span>
                            </div>
                        </td>
                        <td>
                            {this.props.emBtnPayment}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div className="total">
                    <div className="txt">{this.props.txt_total}</div>
                    <div className="val">{this.props.total_price}</div>
                </div>
            </div>
        )
    }
});


module.exports = EmTotalCart;
