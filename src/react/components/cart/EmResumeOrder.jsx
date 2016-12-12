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
        console.log(config['draw_days']);
        return (

            <div className="box-total-upper cl">
                <div className="txt-black-upper desktop">
                    <span className="txt-bold">{config['duration']} Draws</span><br />
                    <span className="txt-bold">{config['draw_days']}Tuesday / Friday / Tuesday and Friday</span><br />
                    {config['draw_days'] != null ? '<span className="txt-bold">Jackpot: </span><span>' + config['draw_days'] + '</span><br />' : ''}
                    {config['startDrawDate'] != null ? '<span className="txt-bold">Starting Date: </span><span>' + config['startDrawDate'] + '</span><br />' : ''}
                </div>
                <div className="total">
                    <div className="txt">TOTAL XXX€</div>
                    <div className="val">{this.props.total_price}</div>
                    <EmBtnPayment href={this.props.href} databtn={this.props.databtn} price={this.props.price}
                                  classBtn={this.props.classBtn} text={this.props.text}/>
                </div>
            </div>
        )
    }
});


module.exports = EmResumeOrder;
