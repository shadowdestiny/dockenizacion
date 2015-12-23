var React = require('react');
var ReactDOM = require('react-dom');
var EmSelectDrawDuration = require('./EmSelectDrawDuration.jsx');

var EmLineOrderConfig = new React.createClass({

    getInitialState : function ()
    {
        return {
            show_select : false
        }
    },


    handleClick : function ()
    {
        if(!this.state.show_select) {
            this.setState({ show_select : true });
        }
    },

    render : function ()
    {
        var options_draw_duration = [
            {text : '1 week (Draw: 1)' , value : 1},
            {text : '2 weeks (Draws: 2)' , value : 2},
            {text : '4 weeks (Draws: 4)' , value : 4},
            {text : '8 weeks (Draws: 8)' , value : 8},
            {text : '52 weeks (Draws: 52)' , value : 52},
        ];
        var default_value_duration = '1';
        var default_text_duration = '1 week (Draw: 1)';
        var date_since = this.props.playConfig.startDrawDate;
        var frequency = this.props.playConfig.frequency;
        var num_weeks = this.props.playConfig.num_weeks;
        var text_weeks = num_weeks > 0 ? 'for ' + num_weeks : '';// + (num_weeks > 1) ? ' weeks' : 'week' : '';
        text_weeks += (num_weeks > 1) ? ' weeks' : 'week';
        var frequency_draws_text = frequency > 1 ? ' draws' : 'draw';
        var select_draw_duration = <div className="val summary">{frequency} {frequency_draws_text}</div>;

        if(this.state.show_select) {
            select_draw_duration = <EmSelectDrawDuration change_duration={this.props.duration} defaultValue={default_value_duration} defaultText={default_text_duration} options={options_draw_duration} active={true}/>;
        }
        return (
            <div className="row cl">
                <div className="desc">
                    Draws
                </div>
                <div className="detail">
                    Since {date_since} {text_weeks}
                </div>
                <div className="right">
                    {select_draw_duration}
                    <a className="change" onClick={this.handleClick} href="javascript:void(0);">Change</a>
                </div>
            </div>
        )
    }


});

module.exports = EmLineOrderConfig;
