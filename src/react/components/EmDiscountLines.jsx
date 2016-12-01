var React = require('react');
var EmDiscountLine = require('./EmDiscountLine.jsx');

var EuroMillionsDiscountLines = new React.createClass({

    render : function () {
        var showtitle = this.props.title ? "block" : "none";
        var discountLine = [];
        var cont=0;
        JSON.parse(this.props.discount_lines).forEach(function (line) {
            console.log(line);
            discountLine.push(<EmDiscountLine key={cont} draws={line.draws} desc={line.description} price_desc={line.price_description}  price={line.price}  discount={line.discount} />);
            cont++;
        });
        return (
            <span>
                <h1 className="purple" style={{display : showtitle}}>{this.props.title}</h1>
                <span>{discountLine}</span>
            </span>
        )
    }

});

module.exports = EuroMillionsDiscountLines;