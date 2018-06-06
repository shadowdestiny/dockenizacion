var React = require('react');

var EmBtnPayment = new React.createClass({

    displayName: 'EmBtnPayment',


    render : function ()
    {
        if(this.props.databtn == 'wallet') {
            return (
                <div className="box-bottom cl">
                    <a href={this.props.href} data-btn={this.props.databtn} className={this.props.classBtn}>{this.props.text}</a>
                </div>
            )
        } else {
            var price = 0;

            if(this.props.powerplay) {
                price = parseFloat(this.props.total_price) + (parseFloat(this.props.total_lines) * parseFloat(this.props.powerplayprice));
                // price = (this.props.price) + price.toFixed(2);
            } else {
                price = this.props.price;
            }

            var value = accounting.formatMoney(price, this.props.currency_symbol, 2);

            return (

                <div className="box-bottom cl">
                    <a href={this.props.href} data-btn={this.props.databtn} className={this.props.classBtn}>
                        {this.props.text}
                        <span className="gap">
                           |
                        </span>
                        {value}
                    </a>
                </div>
            )
        }
    }
});
module.exports = EmBtnPayment;