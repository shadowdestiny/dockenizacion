var React = require('react');
var ReactDOM = require('react-dom');
var EmBtnPayment = require('./EmBtnPayment.jsx');

var EmResumeOrder = new React.createClass({

    displayName: 'EmResumeOrder',


    componentWillReceiveProps : function(newProps) {
        console.log(newProps);
        this.props.price_txt_btn = newProps.text;
        $(document).trigger("totalPriceEvent", [ newProps.pricetopay, newProps.funds ]);
    },

    render : function ()
    {

        console.log('propertiiiiiiiiieeeeeeeessss' + this.props.price_txt_btn);

        return (

            <div className="box-total cl">
                <div className="txt-black desktop">
                    <span>X Draws</span><br />
                    <span>Tuesday / Friday / Tuesday and Friday</span><br />
                    <span>Jackpot: Minimum 70 kilacos</span><br />
                    <span>Starting Date: Next Draw</span><br />
                </div>
                <div className="total">
                    <div className="txt">TOTAL XXX€ </div>
                    <div className="val">{this.props.total_price}</div>
                    <EmBtnPayment href={this.props.href_payment} databtn={this.props.data_btn} price={this.props.price_txt_btn} classBtn={this.props.class_button_payment} text={this.props.txt_button_payment}/>
                </div>
            </div>
        )
    }
});


module.exports = EmResumeOrder;
