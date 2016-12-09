var React = require('react');
var EmSelect = require('./EmSelect');
var ReactTooltip = require("react-tooltip");

var EmSelectDrawDate = React.createClass({


    getInitialState: function () {
        return {
            dates : []
        }
    },

    componentDidMount: function() {
    },

    handleChange: function (event) {
        this.props.change_date(event.target.value);
    },

    render: function () {

        var disabled = !this.props.active;

        var select = <EmSelect
            options={this.props.options}
            defaultValue={this.props.defaultValue}
            defaultText={this.props.defaultText}
            onChange={this.handleChange}
            useTextAsValue={false}
            disabled={disabled}/>;

        var divStyle = {
            display : 'inline-block',
            margin: '2px'
        }
        var labelStyle = {
            color : '#333'
        }

        if(this.props.show == 1) {
            return (
                <div id="BuyForDrawBox">
                    <div style={divStyle}>
                        Buy for Draw:
                    </div>
                    <div style={divStyle}>
                        <label style={labelStyle}>{this.props.defaultText}</label>
                    </div>
                </div>
            )
        }

        return null;

    }

})

module.exports = EmSelectDrawDate