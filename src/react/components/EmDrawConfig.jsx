var React = require('react');

var EmSelectDraw = require('./EmSelectDraw.jsx');
var EmSelectDrawDate = require('./EmSelectDrawDate.jsx');
var EmSelectDrawDuration = require('./EmSelectDrawDuration.jsx');

var EuroMillionsDrawConfig = new React.createClass({

    getInitialState: function () {
        return ({
            selectdrawactive : true
        });
    },

    componentWillReceiveProps : function (nextProps) {

        this.setState( { selectdrawactive : nextProps.active });

    },

    getNameDayFromNumber : function(value) {

        switch(value) {
            case 2:
                return 'Tuesday';
            case 5:
                return 'Friday';
            default:
                return 'Tuesday & Friday';
        }
    },

    render : function () {

        var elem = [];
        var options_draw_dates = [];
        var draw_days_selected = this.props.draw_days_selected;

        this.props.draw_dates.forEach(function(obj,i){
            var obj_split = String(obj).split('#');
            if(draw_days_selected == obj_split[1]) {
                options_draw_dates.push({text : obj_split[0], value : obj_split[0]});
            }
            if(draw_days_selected == 25) {
                options_draw_dates.push({text : obj_split[0], value : obj_split[0]});
            }
        });

        var default_text_date = ""+options_draw_dates[0].text;
        var default_value_date = ""+options_draw_dates[0].text;

        var default_value_draw = this.getNameDayFromNumber(draw_days_selected);
        var default_text_draw = this.getNameDayFromNumber(draw_days_selected);

        var duration_value = this.props.current_duration_value;
        var default_value_duration = ""+this.props.draw_duration[0].value;
        var default_text_duration = ""+this.props.draw_duration[0].text;

        this.props.draw_duration.forEach(function(obj,i){
            if(obj.value == duration_value) {
                default_value_duration = ""+obj.value;
                default_text_duration = ""+obj.text;
            }
        });

        var options_draw_days = [
            {text: 'Tuesday & Friday' , value : '25'},
            {text: 'Tuesday', value : '2'},
            {text: 'Friday' , value : '5'}
        ];

        elem.push(<EmSelectDraw play_days={this.props.play_days} defaultValue={default_value_draw} defaultText={default_text_draw} options={options_draw_days} {...this.props} active={this.state.selectdrawactive} key="1"/>)
        elem.push(<EmSelectDrawDate change_date={this.props.date_play} defaultValue={default_value_date} defaultText={default_text_date} options={options_draw_dates} active={this.state.selectdrawactive} key="2"/>)
        elem.push(<EmSelectDrawDuration change_duration={this.props.duration} defaultValue={default_value_duration} defaultText={default_text_duration} options={this.props.draw_duration} active={this.state.selectdrawactive} key="3"/>)
        return (
            <div>
                {elem}
            </div>
        )
    }
});

module.exports = EuroMillionsDrawConfig;