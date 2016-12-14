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
        var styleStartingDate = config['startDrawDate'] != null ? 'inline' : 'none';
        return (

            <div className="box-total-upper cl">
                <div className="txt-black-upper desktop">
                    <span className="txt-bold">{config['frequency']} Draws</span><br />
                    <span className="txt-bold">{config['draw_days']}Tuesday / Friday / Tuesday and Friday</span><br />

                    <span className="txt-bold" style={{ display: jackpot}}>Jackpot: </span><span style={{display: jackpot}}>{config['draw_days']}</span>
                    <span className="txt-bold" style={{ display: styleStartingDate}}>Starting Date: </span><span style={{display: styleStartingDate}}>{config['startDrawDate']}</span>
                </div>
                <div className="total">
                    <div className="txt">TOTAL </div>
                    <div className="val">{this.props.total_price}</div>
                    <EmBtnPayment href={this.props.href} databtn={this.props.databtn} price={this.props.total_price}
                                  classBtn={this.props.classBtn} text={this.props.text}/>
                </div>
            </div>
        )
    }
});


module.exports = EmResumeOrder;
