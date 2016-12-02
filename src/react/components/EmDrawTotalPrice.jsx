var React = require('react');

var EuroMillionsDiscountLines = new React.createClass({

    render : function () {
        var total_price = this.props.num_lines * this.props.draws_number;

        return (
            <span className="drawTotal">
                TOTAL: {this.props.num_lines} Lines x {this.props.draws_number} Draws = {total_price} &euro;
            </span>
        )
    }

});

module.exports = EuroMillionsDiscountLines;