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

        if(this.props.show == 1 && this.props.next_draw == 2) {
            return (
                <div id="BuyForDrawBox">
                    <div style={divStyle}>
                        {this.props.buyForDraw}:
                    </div>
                    <div style={divStyle}>
                        <label style={labelStyle}>
                            {this.props.tuesday} {this.props.next_draw_date_format}</label>
                    </div>
                </div>
            )
        } else if(this.props.show == 1 && this.props.next_draw == 5) {
            return (
                <div id="BuyForDrawBox">
                    <div style={divStyle}>
                        {this.props.buyForDraw}:
                    </div>
                    <div style={divStyle}>
                        <label style={labelStyle}>
                            {this.props.friday} {this.props.next_draw_date_format}</label>
                    </div>
                </div>
            )
        }


        return null;

    }

})

module.exports = EmSelectDrawDate