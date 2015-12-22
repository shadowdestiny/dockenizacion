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
        var options_draw_dates = [];

        draw_dates.forEach(function(obj,i){
            options_draw_dates.push({text : obj, value : i});
        });

        var default_value_draw = '2';
        var default_text_draw = 'Tuesday';


        var default_value_duration = '1';
        var default_text_duration = '1 week (Draw: 1)';

        var options_draw_duration = [
            {text : '1 week (Draw: 1)' , value : 1},
            {text : '2 weeks (Draws: 2)' , value : 2},
            {text : '4 weeks (Draws: 4)' , value : 4},
            {text : '8 weeks (Draws: 8)' , value : 8},
            {text : '52 weeks (Draws: 52)' , value : 52},
        ];


        elem.push(<EmSelectDraw play_days={this.props.play_days} defaultValue={default_value_draw} defaultText={default_text_draw} {...this.props} active={this.state.selectdrawactive} key="1"/>)
        elem.push(<EmSelectDrawDate change_date={this.props.date_play} options={options_draw_dates} active={this.state.selectdrawactive} key="2"/>)
        elem.push(<EmSelectDrawDuration change_duration={this.props.duration} defaultValue={default_value_duration} defaultText={default_text_duration} options={options_draw_duration} active={this.state.selectdrawactive} key="3"/>)
        return (
            <div>
                {elem}
            </div>
        )
    }
});

module.exports = EuroMillionsDrawConfig;