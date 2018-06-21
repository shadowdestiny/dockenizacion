var React = require('react');

var EmBtnPayment = new React.createClass({

    displayName: 'EmBtnPayment',


    render : function ()
    {
        var frequency = 1;
        if (this.props.config) {
            frequency = this.props.config.frequency;
        }

        if(this.props.databtn == 'wallet') {

            return (
                <div className="box-bottom cl">
                    <a href={this.props.href} data-btn={this.props.databtn} className={this.props.classBtn}>{this.props.text}</a>
                </div>
            )
        } else {
//             var price = 0;
// // console.log(this.props.fee);
//             // console.log(this.props.price);
//
//             if(this.props.powerplay) {
//                 price = parseFloat(this.props.total_price) + ((parseFloat(this.props.total_lines) * parseFloat(this.props.powerplayprice)) * frequency);
//                 // price = (this.props.price) + price.toFixed(2);
//             } else {
//                 price = this.props.price;
//             }
//
//             if (price <= 12) {
//                 price = parseFloat(price) + parseFloat(this.props.fee);
//             }
//             alert(price);
//             var value = accounting.formatMoney(price, this.props.currency_symbol, 2);

            return (

                <div className="box-bottom cl">
                    <a href={this.props.href} data-btn={this.props.databtn} className={this.props.classBtn}>
                        {this.props.text}
                        <span className="gap">
                           |
                        </span>
                        {this.props.price}
                    </a>
                </div>
            )
        }
    }
});
module.exports = EmBtnPayment;