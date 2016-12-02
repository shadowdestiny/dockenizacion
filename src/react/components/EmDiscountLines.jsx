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
        var cont=0;
        var sendLineSelected = this.props.sendLineSelected;
        var drawsNumber = this.state.draws_number;
        JSON.parse(this.props.discount_lines).forEach(function (line) {
            if (line.checked == 'checked') {
                drawsNumber = line.draws;
            }
            discountLine.push(<EmDiscountLine sendLineSelected={sendLineSelected} key={cont} draws={line.draws} desc={line.description} price_desc={line.price_description}  price={line.price}  discount={line.discount} checked={line.checked} />);
            cont++;
        });

        return (
            <div>
                <h1 className="purple" style={{display : showtitle}}>{this.props.title}</h1>
                <ul className="filter-discounts">
                    {discountLine}
                </ul>
            </div>
        )
    }

});

module.exports = EuroMillionsDiscountLines;