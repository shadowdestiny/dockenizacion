var React = require('react');
var ReactDOM = require('react-dom');
var EmSelectDrawDuration = require('./../EmSelectDrawDuration.jsx');

var EmLineOrderConfig = new React.createClass({

    displayName: 'EmLineOrderConfig',

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

    handleChange : function (event)
    {
        this.props.duration(event.target.value);
    },

    render : function ()
    {
        var date_since = this.props.playConfig.startDrawDate;
        var frequency = this.props.playConfig.frequency;
        var num_weeks = this.props.playConfig.num_weeks;
        var draw_days = this.props.playConfig.drawDays;
        var text_weeks = '';//num_weeks > 0 ? 'for ' + num_weeks : '';// + (num_weeks > 1) ? ' weeks' : 'week' : '';

        var date = new Date(date_since);
        var day = '';
        if(date.getDay() == 2 ) {
            day = 'Tuesday';
        } else if(date.getDay() == 5 ) {
            day = 'Friday';
        }

        if(draw_days == 1 && num_weeks == 0) {
            text_weeks = ' On ' + day + ' ' + date_since;
        } else if ( draw_days > 1 && num_weeks == 0) {
            text_weeks = 'Tuesday & Friday, since ' + date_since + ' for 1 week';
        } else if ( draw_days == 1 && num_weeks > 0) {
            text_weeks = 'Every ' + day +', since ' + date_since + ' for ' + num_weeks + ' weeks';
        } else if ( draw_days > 1 && num_weeks > 0) {
            text_weeks = 'Tuesday & Friday, since ' + date_since + ' for ' + num_weeks + ' weeks';
        }
        var frequency_draws_text = frequency > 1 ? 'draws' : 'draw';
        var select_draw_duration = '';
        var change_link = <a className="change" onClick={this.handleClick} href="javascript:void(0);">Change {frequency} {frequency_draws_text}</a>;

        var options_text = [];

        options_text.push(<option key="1" value="1">1 draw</option>);
        options_text.push(<option key="2" value="2">2 draws</option>);
        options_text.push(<option key="3" value="4">4 draws</option>);
        options_text.push(<option key="4" value="8">8 draws</option>);
        options_text.push(<option key="5" value="52">52 draws</option>);

        if(draw_days > 1) {
            options_text = [];
            options_text.push(<option key="1" value="2">2 draws</option>);
            options_text.push(<option key="2" value="4">4 draws</option>);
            options_text.push(<option key="3" value="8">8 draws</option>);
            options_text.push(<option key="4"  value="16">16 draws</option>);
            options_text.push(<option key="5" value="104">104 draws</option>);
        }

        if(this.state.show_select) {
            //select_draw_duration = <div className="val summary">
            //    <select onChange={this.handleChange} className="mySelect">
            //        <option value="0" defaultValue>Select...</option>
            //        {options_text}
            //    </select>
            //    </div>;
            //change_link = '';
            select_draw_duration = '';
            change_link = '';
        }
        select_draw_duration = '';
        change_link = '';

        return (
            <div className="row cl">
                <div className="desc">
                    Draws
                </div>
                <div className="detail">
                    {text_weeks}
                </div>
                <div className="right">
                    {select_draw_duration}
                    {change_link}
                </div>
            </div>
        )
    }

});

module.exports = EmLineOrderConfig;
