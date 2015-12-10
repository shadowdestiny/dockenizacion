var React = require('react');

var EmSelectDraw = require('./EmSelectDraw.jsx');
var EmSelectDrawDate = require('./EmSelectDrawDate.jsx');

var EuroMillionsDrawConfig = new React.createClass({

    getInitialState: function () {
        return ({
             selectdrawactive : true,
        });
    },

    render : function () {

        var elem = [];
        elem.push(<EmSelectDraw {...this.props} active={this.state.selectdrawactive} key="1"/>)
        elem.push(<EmSelectDrawDate {...this.props} active={this.state.selectdrawactive} key="2"/>)

        return (
            <div>
                {elem}
            </div>
        )
    }
});

module.exports = EuroMillionsDrawConfig;