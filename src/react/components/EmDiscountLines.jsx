var React = require('react');
var EmDiscountLine = require('./EmDiscountLine.jsx');

var EuroMillionsDiscountLines = new React.createClass({


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
        JSON.parse(this.props.discount_lines).forEach(function (line,i) {
            let isChecked = (line.draws == rowSelected);
            discountLine.push(<EmDiscountLine currency_symbol={currencySymbol} sendLineSelected={sendLineSelected} key={i} draws={line.draws} desc={line.description} price_desc={line.price_description}  price={line.price}  discount={line.discount} checked={isChecked} />);
        });

        return (
            <div>
                <h1 className="purple" style={{display : showtitle}}>{this.props.title}</h1>
                {discountLine}
            </div>
        )
    }

});

module.exports = EuroMillionsDiscountLines;