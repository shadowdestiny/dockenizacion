var React = require('react');
import EuroMillionsNumber from './EmNumber.js';
var createReactClass = require('create-react-class');
var EuroMillionsLineRow = createReactClass({

    random : function () {

    },

    render: function () {
        var numbers = [];
        var selected = false;
        var selected_numbers = this.props.selectedNumbers.numbers;
        var onNumberClick = this.props.onNumberClick;
        var random_animation = this.props.random_animation;
        this.props.numbers.forEach(function (number) {
            selected = selected_numbers.indexOf(number) != -1;
            numbers.push(<EuroMillionsNumber random_animation={random_animation} onNumberClick={onNumberClick} number={number} key={number} selected={selected}/>);
        });
        return (
            <ol className="no-li cols not">
                {numbers}
            </ol>
        );
    }
});

export default  EuroMillionsLineRow;