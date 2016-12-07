var React = require('react');
var EuroMillionsAddLinesBtn = require('./EmAddLinesBtn.jsx');
var EuroMillionsRandomAllBtn = require('./EmRandomAllBtn.jsx');
var EuroMillionsClearAllBtn = require('./EmClearAllBtn.jsx');
var EmSelectDrawDate = require('./EmSelectDrawDate.jsx');

var EuroMilliosnBoxActionPlay = React.createClass({

    displayName : 'EuroMilliosnBoxActionPlay',

    handlerAddLines : function() {
        this.props.add_lines(null);
    },
    handlerRandomAll : function() {
        this.props.random_all_btn(null);

    },
    handlerClearAll : function () {
       this.props.clear_all_btn(null)
    },

    render : function() {
        var elem = [];
        var show_btn = this.props.show_clear_all;

        var options_draw_dates = [];
        this.props.draw_dates.forEach(function(obj,i){
            var obj_split = String(obj).split('#');
            options_draw_dates.push({text : obj_split[0]+' 20:00', value : obj_split[0]});
        });

        var default_text_date = ""+options_draw_dates[0].text;
        var default_value_date = ""+options_draw_dates[0].text;
        var selectDrawDate = <EmSelectDrawDate show={this.props.showBuyDrawDate} change_date={this.props.date_play} defaultValue={default_value_date} defaultText={default_text_date} options={options_draw_dates} active={true}/>

        elem.push(<EuroMillionsAddLinesBtn show_tooltip={this.props.show_tooltip}  mouse_over_btn={this.props.mouse_over_btn} onBtnAddLinesClick={this.handlerAddLines} key="1"/>);
        elem.push(<EuroMillionsRandomAllBtn onBtnRandomAllClick={this.handlerRandomAll} key="2"/>);
        elem.push(<EuroMillionsClearAllBtn show_btn_clear={show_btn} onBtnClearAllClick={this.handlerClearAll} key="3"/>);

        return (
            <div className="cl" id="box-action">
                <ul className="no-li cl box-action">
                    {elem}
                </ul>
                <div className="info right">
                    {selectDrawDate}
                </div>
            </div>
        );
    }
})

module.exports = EuroMilliosnBoxActionPlay;