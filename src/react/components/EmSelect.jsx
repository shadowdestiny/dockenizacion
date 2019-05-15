var React = require('react');
var  PropTypes = require ('prop-types');
var createReactClass = require('create-react-class');
var EmSelect = createReactClass({
    propTypes: {
        options: PropTypes.array.isRequired,
        defaultValue: PropTypes.string,
        defaultText: PropTypes.string,
        classDiv: PropTypes.string,
        classSelect: PropTypes.string
    },
    getDefaultProps: function () {
        return {
            defaultValue: null,
            classDiv: 'select-txt',
            classBox: 'styled-select',
            classSelect: 'threshold mySelect',
            disabled: 'false',
            hidden: 'false',
            useTextAsValue : false
        };
    },
    getInitialState: function () {
        return {
            value: this.props.defaultValue,
            divText: this.props.defaultText
        };
    },

    componentWillReceiveProps : function (nextProps) {
        if (nextProps.defaultText != this.props.defaultText) {
            this.setState( { divText : nextProps.defaultText })
        }
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
        var textAsValue = this.props.useTextAsValue;
        this.props.options.forEach(function(option) {
            var option_value = textAsValue ? option.text : option.value;
            options.push(<option value={option_value} key={option.value}>{option.text}</option>);
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
export default  EmSelect;