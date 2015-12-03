var React = require('react');

var EmSelect = React.createClass({
    propTypes: {
        options: React.PropTypes.array.isRequired,
        defaultValue: React.PropTypes.string,
        defaultText: React.PropTypes.string,
        classDiv: React.PropTypes.string,
        classSelect: React.PropTypes.string
    },
    getDefaultProps: function () {
        return {
            defaultValue: null,
            classDiv: 'select-txt',
            classBox: 'styled-select',
            classSelect: 'threshold mySelect',
            disabled: 'false',
            hidden: 'false'
        };
    },
    getInitialState: function () {
        return {
            value: this.props.defaultValue,
            divText: this.props.defaultText
        };
    },
    handleChange: function (event) {
        if (this.props.onChange == undefined || this.props.onChange(event) !== false) {
            this.setState({
                value: event.target.value,
                divText: event.target.selectedOptions[0].text
            });
        } else {
            this.setState({
                value: this.state.value
            });
        }
    },
    render: function () {
        var options = [];
        this.props.options.forEach(function(option) {
            options.push(<option value={option.value} key={option.value}>{option.text}</option>);
        });
        var box_class = this.props.disabled ? this.props.classBox+' disabled': this.props.classBox;
        return (
            <div className={box_class}>
                <div className={this.props.classDiv}>{this.state.divText}</div>
                <select value={this.state.value} onChange={this.handleChange} className={this.props.classSelect} disabled={this.props.disabled}>
                    {options}
                </select>
            </div>
        );
    }
});
module.exports = EmSelect;