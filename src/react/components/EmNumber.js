var React = require('react');

var EuroMillionsNumber = React.createClass({
    getDefaultProps: function()
    {
        return {
            selected: false
        }
    },
    propTypes: {
        number: React.PropTypes.number.isRequired,
        selected: React.PropTypes.bool,
        onNumberClick: React.PropTypes.func.isRequired
    },
    render: function () {
        var class_name = this.props.selected ? "btn gwp n" + this.props.number + " active" : "btn gwp n" + this.props.number;
        var button = <a className={class_name} onClick={this.props.onNumberClick.bind(null, this.props.number)} href="javascript:void(0);">{this.props.number}</a>;
        return (<li className="col20per not">{button}</li>);
    }
});

module.exports = EuroMillionsNumber;