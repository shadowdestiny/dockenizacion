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
            day = 'Tuesday';
        } else if(date.getDay() == 5 ) {
            day = 'Friday';
        }

        if(config.frequency == 1) {
            text_weeks = ' ' + this.props.txt_on + ' ' + day + ' ' + date_since;
        } else {
            text_weeks = 'Tuesday & Friday, since ' + date_since + ' for ' + config.frequency / 2 + ' weeks';
        }

        return (
            <div className="box-order">
                <div className="box-total-upper cl">
                    <div className="txt-black-upper">
                        <h1>{this.props.txt_summary}</h1>
                        <span className="txt-bold">{this.props.txt_draws} x{config['frequency']}</span><br />
                        <span className="txt-bold">{text_weeks}</span><br />
                        <span className="txt-bold" style={{ display: jackpot}}>Jackpot: </span><span style={{display: jackpot}}>{config['draw_days']}</span>
                    </div>
                    <div className="total">
                        <div className="txt">{this.props.txt_total}</div>
                        <div className="val">{this.props.total_price}</div>
                        <EmBtnPayment href={this.props.href} databtn={this.props.databtn} price={this.props.total_price}
                                      classBtn={this.props.classBtn} text={this.props.text}/>
                    </div>
                    <div className="edit-btn-container">
                      <a href="/euromillions/play" className="btn purple small ui-link">
                        Edit
                      </a>
                    </div>
                </div>
            </div>
        )
    }
});


module.exports = EmResumeOrder;
