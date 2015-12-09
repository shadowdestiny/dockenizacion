var React = require('react');
var EuroMillionsNumber = require('./EmNumber.js');

var EuroMillionsLineRow = React.createClass({

    random : function () {

    },

    render: function () {
        var numbers = [];
        var selected = false;
        var selected_numbers = this.props.selectedNumbers.numbers;
        var onNumberClick = this.props.onNumberClick;

        this.props.numbers.forEach(function (number) {
            selected = selected_numbers.indexOf(number) != -1;
            numbers.push(<EuroMillionsNumber onNumberClick={onNumberClick} number={number} key={number} selected={selected}/>);
        });

        return (
            <ol className="no-li cols not">
                {numbers}
            </ol>
        );
    }
});

module.exports = EuroMillionsLineRow;