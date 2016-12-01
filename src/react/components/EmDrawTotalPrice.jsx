var React = require('react');

var EuroMillionsDiscountLines = new React.createClass({

    render : function () {

        return (
            <span>
                <h1>TOTAL: {this.props.num_lines} Lines x {this.props.draws_number} Draws = {this.props.num_lines * this.props.draws_number} &euro;</h1>
            </span>
        )
    }

});

module.exports = EuroMillionsDiscountLines;