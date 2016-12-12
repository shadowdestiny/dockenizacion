var React = require('react');
var ReactDOM = require('react-dom');
var EmBtnPayment = require('./EmBtnPayment.jsx');

var EmResumeOrder = new React.createClass({

    displayName: 'EmResumeOrder',


    componentWillReceiveProps : function(newProps) {
        $(document).trigger("totalPriceEvent", [ newProps.pricetopay, newProps.funds ]);
    },

    render : function ()
    {

        return (

            <div className="box-total-upper cl">
                <div className="txt-black-upper desktop">
                    <span className="txt-bold">X Draws</span><br />
                    <span className="txt-bold">Tuesday / Friday / Tuesday and Friday</span><br />
                    <span className="txt-bold">Jackpot: </span><span>Minimum 70 kilacos</span><br />
                    <span className="txt-bold">Starting Date: </span><span>Next Draw</span><br />
                </div>
                <div className="total">
                    <div className="txt">TOTAL XXX€ </div>
                    <div className="val">{this.props.total_price}</div>
                    <EmBtnPayment href={this.props.href} databtn={this.props.databtn} price={this.props.price} classBtn={this.props.classBtn} text={this.props.text}/>
                </div>
            </div>
        )
    }
});


module.exports = EmResumeOrder;
