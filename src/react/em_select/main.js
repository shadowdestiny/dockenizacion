var React = require('react');
var ReactDOM = require('react-dom');

var EmSelect = React.createClass({
    propTypes: {
        options: React.PropTypes.array.isRequired,
        defaultValue: React.PropTypes.string,
        defaultText: React.PropTypes.string,
        classDiv: React.PropTypes.string,
        classSelect: React.PropTypes.string
    },
    getDefaultProps: function() {
        return {
            defaultValue: null,
            classDiv: 'select-txt',
            classSelect: 'threshold mySelect',
        };
    },
    getInitialState: function() {
        return {
            value: this.props.defaultValue,
            divText: this.props.defaultText
        };
    },
    handleChange: function(event) {
        this.setState({
            value: event.target.value,
            divText: event.target.selectedOptions[0].text
        });
    },
    render: function () {
        var options = [];
        this.props.options.forEach(function(option) {
            options.push(<option value={option.value} key={option.value}>{option.text}</option>);
        });
        return (
            <div>
                <div className={this.props.classDiv}>{this.state.divText}</div>
                <select value={this.state.value} onChange={this.handleChange} className={this.props.classSelect}>
                    {options}
                </select>
            </div>
        );
    }
});
module.exports = EmSelect;