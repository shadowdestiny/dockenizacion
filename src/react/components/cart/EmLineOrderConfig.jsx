var React = require('react');
var ReactDOM = require('react-dom');
import EmSelectDrawDuration from './../EmSelectDrawDuration.jsx';
var createReactClass = require('create-react-class');
var EmLineOrderConfig = createReactClass({

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

    handlePreTotal : function(days, weeks)
    {
        var value = days * weeks;
        this.props.pre_total(value);
    },

    handleChange : function (event)
    {
        this.props.duration(event.target.value);
    },

    render : function ()
    {

        var config = JSON.parse(this.props.config);
        var date_since = config.startDrawDate;
        var num_weeks = config.frequency > 0 ? config.frequency : 1;
        var draw_days = 1;
        var text_weeks = '';//num_weeks > 0 ? 'for ' + num_weeks : '';// + (num_weeks > 1) ? ' weeks' : 'week' : '';
        this.handlePreTotal(draw_days,num_weeks);

        var date = new Date(date_since);
        var day = '';
        if(date.getDay() == 2 ) {
            day = 'Tuesday';
        } else if(date.getDay() == 5 ) {
            day = 'Friday';
        }


        if(draw_days == 1 && num_weeks == 1) {
            text_weeks = ' On ' + day + ' ' + date_since;
        } else if ( draw_days > 1 && num_weeks == 1) {
            text_weeks = 'Tuesday & Friday, since ' + date_since + ' for 1 week';
        } else if ( draw_days == 1 && num_weeks > 1) {
            text_weeks = 'Every ' + day +', since ' + date_since + ' for ' + num_weeks + ' weeks';
        } else if ( draw_days > 1 && num_weeks > 1) {
            text_weeks = 'Tuesday & Friday, since ' + date_since + ' for ' + num_weeks + ' weeks';
        }

        return null;
        if(draw_days == 1 && num_weeks == 1) {
            return (
                <div className="row cl">
                    <div className="box-detail">
                        <div className="desc">Draw</div>
                        <div className="detail">{text_weeks}</div>
                    </div>
                </div>
            )
        } else {
            return (
                <div className="row cl">
                    <div className="box-detail">
                        <div className="desc">Draw</div>
                        <div className="detail">{text_weeks}</div>
                    </div>
                    <div className="quantity">
                        <span className="txt">Draws</span> <span className="val">x{num_weeks * draw_days}</span>
                    </div>
                </div>

            )
        }
    }

});

export default  EmLineOrderConfig;
