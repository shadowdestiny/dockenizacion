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
        let text_last_weeks = '';

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

        if(this.props.txt_lottery === 'Eurojackpot') {
            txt_link_play = "/" + this.props.txt_link_eurojackpot;
        } else if(this.props.txt_lottery === 'Megamillions') {
            txt_link_play = "/" + this.props.txt_link_megamillions;
        } else if(this.props.txt_lottery === 'Powerball') {
            txt_link_play = "/" + this.props.txt_link_powerball;
        } else {
            txt_link_play = "/" + this.props.txt_link_play;
        }

        let format = function(date,sum_day){
            let ar_date = date.split('.');
            let day     = ar_date[0];
            let month   = ar_date[1];
            let year    = ar_date[2];

            let new_date = new Date(year+"-"+month+"-"+day);
            new_date.setDate(new_date.getDate() + sum_day + 1);
            return new_date.getDate()+"."+(new_date.getMonth() + 1 < 10 ? "0" : "") + new_date.getMonth()+"."+new_date.getFullYear();
        };

        if(this.props.txt_lottery === 'Euromillions') {
            text_weeks = this.props.tuesday + ', ' + config.startDrawDateFormat;
            let end_date = format(config.startDrawDateFormat,2);
            text_last_weeks = this.props.friday + ', ' + end_date;
        } else if(this.props.txt_lottery === 'Powerball') {
            text_weeks = this.props.wednesday  + ', ' + config.startDrawDateFormat;
            let end_date = format(config.startDrawDateFormat,3);
            text_last_weeks = this.props.saturday + ', ' + end_date;
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
                        <table className={"order-table-resume"}>
                            <tbody>
                            <tr>
                                <td className={"box-left-table-width"}>
                                    <span className="txt-bold format">{this.props.txt_summary}</span>
                                </td>
                                <td>
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h1 className={"order-text-format"}>
                                        { this.props.txt_lottery_title+":" }
                                    </h1>
                                </td>
                                <td>
                                    <span className="txt-bold format">{this.props.txt_lottery}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h1 className={"order-text-format"}>
                                        { this.props.txt_number_of_draws+":" }
                                    </h1>
                                </td>
                                <td>
                                    <span className="txt-bold format">{config['frequency']}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h1 className={"order-text-format"}>
                                        { this.props.txt_starting_date+":" }
                                    </h1>
                                </td>
                                <td>
                                    <span className="txt-bold format">{text_weeks}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h1 className={"order-text-format"}>
                                        { this.props.txt_ending_date+":" }
                                    </h1>
                                </td>
                                <td>
                                    <span className="txt-bold format">{text_last_weeks}</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        {/*<h1>{this.props.txt_lottery}</h1><br />
                        <h1>{this.props.txt_draws} x{config['frequency']}</h1><br />
                        <h1>{text_weeks}</h1><br />
                        <h1 style={{ display: jackpot}}>Jackpot: </h1><br />
                        <h1 style={{display: jackpot}}>Jackpot: </h1><br />
                        <span className="txt-bold">{config['draw_days']}</span><br />*/}
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
