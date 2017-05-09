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
                    {this.props.txt_currencyAlert}
                </div>
                <div className="total">
                    <div className="txt">{this.props.txt_total}</div>
                    <div className="val">{this.props.total_price}</div>
                </div>
            </div>
        )
    }
});


module.exports = EmTotalCart;
