var React = require('react');
import EmDiscountLine from './EmDiscountLine.jsx';
var createReactClass = require('create-react-class');
var EuroMillionsDiscountLines = createReactClass({


    getInitialState: function() {
        return {
            draws_number: 0
        }
    },

    render : function () {
        var showtitle = this.props.title ? "block" : "none";
        var discountLine = [];
        var sendLineSelected = this.props.sendLineSelected;
        var rowSelected = this.props.rowSelected;
        var currencySymbol = this.props.currency_symbol;
        var nextDraw = this.props.next_draw;

        JSON.parse(this.props.discount_lines).forEach(function (line,i) {
            let isChecked = (line.draws == rowSelected);
            discountLine.push(<EmDiscountLine next_draw={nextDraw} currency_symbol={currencySymbol} sendLineSelected={sendLineSelected} key={i} draws={line.draws} desc={line.description} price_desc={line.price_description} multi_price={line.price} price={line.singleBetPriceWithDiscount} multi_number={line.multi_number} discount={line.discount} checked={isChecked} />);
        });

        return (
            <div className="draws-section">
                <span className="black" style={{display : showtitle}}>{this.props.title}</span>
                {discountLine}
            </div>
        )
    }

});

// module.exports = EuroMillionsDiscountLines;
export default EuroMillionsDiscountLines;
