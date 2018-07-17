var React = require('react');
var ReactDOM = require('react-dom');
var EmBtnPayment = require('./EmBtnPayment.jsx');

var EmResumeOrder = new React.createClass({

    displayName: 'EmResumeOrder',


    componentWillReceiveProps : function(newProps) {
        $(document).trigger("totalPriceEvent", [ newProps.pricetopay, newProps.funds ]);
    },

    render: function () {
        var config = JSON.parse(this.props.config);
        var jackpot = config['draw_days'] != null ? 'block' : 'none';

        var date_since = config.startDrawDate;
        var text_weeks = '';

        var date = new Date(date_since);
        var day = '';
        if(date.getDay() == 2 ) {
            day = this.props.tuesday;
        } else if(date.getDay() == 5 ) {
            day = this.props.friday;
        } else if(date.getDay() == 3 ) {
            day = this.props.wednesday;
        } else if(date.getDay() == 6 ) {
            day = this.props.saturday;
        }

        if(this.props.txt_lottery === 'Powerball') {
            txt_link_play = "/" + this.props.txt_link_powerball;
        } else {
            txt_link_play = "/" + this.props.txt_link_play;
        }
        if(config.frequency == 1) {
            text_weeks = day + ', ' + config.startDrawDateFormat;
        } else if(this.props.txt_lottery === 'Euromillions') {
            text_weeks = this.props.tuesday + ' & ' + this.props.friday + ', ' + this.props.txt_since + ' ' + config.startDrawDateFormat + ' ' + this.props.txt_for + config.frequency / 2 + ' ' + this.props.txt_weeks;
        } else if(this.props.txt_lottery === 'Powerball') {
            text_weeks = this.props.wednesday + ' & ' + this.props.saturday + ', ' + this.props.txt_since + ' ' + config.startDrawDateFormat + ' ' + this.props.txt_for + config.frequency / 2 + ' ' + this.props.txt_weeks;
        }


        return (
            <div className="box-order">
                <div className="box-total-upper cl">

                    <div className="edit-btn-container">
                        <a href={txt_link_play} className="btn purple small ui-link">
                            {this.props.txt_edit}
                        </a>
                    </div>

                    <div className="txt-black-upper">
                        <h1>{this.props.txt_summary}</h1>
                        <span className="txt-bold">{this.props.txt_lottery}</span><br />
                        <span className="txt-bold">{this.props.txt_draws} x{config['frequency']}</span><br />
                        <span className="txt-bold">{text_weeks}</span><br />
                        <span className="txt-bold" style={{ display: jackpot}}>Jackpot: </span><span style={{display: jackpot}}>{config['draw_days']}</span>
                    </div>

                    <div className="total">
                        <div className="txt">{this.props.txt_total}</div>
                        <div className="val">{this.props.total_price}</div>
                        <EmBtnPayment href={this.props.href} databtn={this.props.databtn} price={this.props.total_price} currency_symbol={this.props.currency_symbol}
                                      classBtn={this.props.classBtn} text={this.props.text}/>
                    </div>

                </div>
            </div>
        )
    }
});


module.exports = EmResumeOrder;
