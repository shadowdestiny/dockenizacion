var React = require('react');
var EuroMillionsStar = require('./EmStar.js');

var EuroMillionsLineStarsRow = React.createClass({
    render: function () {
        var numbers = [];
        var selected = false;
        var selected_numbers = this.props.selectedNumbers.stars ? this.props.selectedNumbers.stars : [];
        var class_name = "no-li cols not" + this.props.extraClass;
        var column_class = this.props.columnClass;
        var onStarClick = this.props.onStarClick;
        var random_animation = this.props.random_animation;

        this.props.numbers.forEach(function (number) {
            selected = selected_numbers.indexOf(number) != -1;
            numbers.push(<EuroMillionsStar random_animation={random_animation} number={number} key={number} onStarClick={onStarClick} selected={selected}
                                           columnClass={column_class}/>);
        });
        return (
            <ol className={class_name}>
                {numbers}
            </ol>
        );
    }
});

module.exports = EuroMillionsLineStarsRow;