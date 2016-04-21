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
            <div className="box-total cl">
                <div className="txt-currency desktop">
                    Currencies are just informative, transactions are charged in Euros.
                </div>
                <div className="total">
                    <div className="txt">Total to be paid </div>
                    <div className="val">{this.props.total_price}</div>
                </div>
            </div>
        )
    }
});
module.exports = EmTotalCart;