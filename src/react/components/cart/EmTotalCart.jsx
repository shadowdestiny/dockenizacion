var React = require('react');
var ReactDOM = require('react-dom');


var EmTotalCart = new React.createClass({

    displayName: 'EmTotalCart',

    render : function ()
    {
        return (
            <div>
                <div className="txt-currency desktop">
                    Currencies are just informative, transactions are charged in Euros.
                </div>

                <div className="total">
                    <div className="txt">Total</div><div className="val">&euro; {this.props.total_price}</div>
                </div>
            </div>

        )
    }
});
module.exports = EmTotalCart;