var React = require('react');
var EmSelectDrawDate = require('./EmSelectDrawDate');

var EuroMillionsPlayNextDrawDate = React.createClass({

    displayName : 'EmPlayNextDrawDate',



    render : function() {

        var options_draw_dates = [];
        this.props.draw_dates.forEach(function(obj,i){
            var obj_split = String(obj).split('#');
                options_draw_dates.push({text : obj_split[0], value : obj_split[0]});
        });

        var default_text_date = ""+options_draw_dates[0].text;
        var default_value_date = ""+options_draw_dates[0].text;

        var selectDrawDate =  <EmSelectDrawDate change_date={this.props.date_play} buyForDraw={this.props.buyForDraw} defaultValue={default_value_date} defaultText={default_text_date} options={options_draw_dates} active={true}/>

        return (
            <div>
                {{selectDrawDate}}
            </div>
        )
    }
})

module.exports = EuroMillionsPlayNextDrawDate;