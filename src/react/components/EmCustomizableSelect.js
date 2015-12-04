var React = require('react');
var EmSelect = require('./EmSelect.js');

var EmCustomizableSelect = React.createClass({
    getInitialState: function () {
        return {
            hideSelect: false,
            inputValue: '85000000'
        }
    },
    componentWillReceiveProps: function (nextProps) {
        this.setState(
            {
                hideSelect: !nextProps.active ? false : this.state.hideSelect
            }
        );
    },
    handleChange: function (event) {
        if (event.target.value == this.props.customValue) {
            this.setState({
                hideSelect: true
            });
            return false;
        }
    },
    handleInputChange: function (event) {
        this.setState(
            {
                inputValue: event.target.value
            }
        );
    },
    render: function () {
        var disabled = !this.props.active;
        var input = this.state.hideSelect ?
            <span className="input-value">
                <input type="text" value={this.state.inputValue} onChange={this.handleInputChange} placeholder="Insert numeric value"/>
            </span>
            : null;
        var select = this.state.hideSelect ?
            null
            : <EmSelect
            options={this.props.options}
            defaultValue={this.props.defaultValue}
            defaultText={this.props.defaultText}
            onChange={this.handleChange}
            disabled={disabled}/>;

        return (
            <div className="details">
                <span className="txt">When Jackpot reach</span>
                {input}
                {select}
            </div>
        );
    }
});

module.exports = EmCustomizableSelect;