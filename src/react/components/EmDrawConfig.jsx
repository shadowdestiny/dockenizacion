var React = require('react');

var EmSelectDraw = require('./EmSelectDraw.jsx');
var EmSelectDrawDate = require('./EmSelectDrawDate.jsx');
var EmSelectDrawDuration = require('./EmSelectDrawDuration.jsx');

var EuroMillionsDrawConfig = new React.createClass({

    getInitialState: function () {
        return ({
             selectdrawactive : true,
        });
    },

    render : function () {

        var elem = [];
        var options_draw_dates = [
            {text: '11 Dec 2015' , value : '1'},
            {text: '15 Dec 2015', value : '2'},
            {text: '18 Dec 2015' , value : '3'},
            {text: '22 Dec 2015' , value : '4'},
            {text: '25 Dec 2015' , value : '5'}
        ];

        var options_draw_duration = [
            {text : '1 week (Draw: 1)' , value : '1'},
            {text : '2 weeks (Draws: 2)' , value : '2'},
            {text : '4 weeks (Draws: 4)' , value : '4'},
            {text : '8 weeks (Draws: 8)' , value : '8'},
            {text : '52 weeks (Draws: 52)' , value : '52'},
        ];

        elem.push(<EmSelectDraw {...this.props} active={this.state.selectdrawactive} key="1"/>)
        elem.push(<EmSelectDrawDate options={options_draw_dates} active={this.state.selectdrawactive} key="2"/>)
        elem.push(<EmSelectDrawDuration options={options_draw_duration} active={this.state.selectdrawactive} key="3"/>)
        return (
            <div>
                {elem}
            </div>
        )
    }
});

module.exports = EuroMillionsDrawConfig;