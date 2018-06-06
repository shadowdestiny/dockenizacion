var React = require('react');

var EuroMillionsAddToCart = new React.createClass({

    propTypes: {
        onBtnAddToCartClick : React.PropTypes.func.isRequired
    },
    render : function () {

        var class_name = 'btn add-cart';
        var price = 0;
        if(this.props.price > 0) class_name = class_name + ' active';
        if(this.props.enablePowerPlay) {
            price = parseFloat(this.props.price) + parseFloat(this.props.powerplay*(this.props.price/3.5));
            price = price.toFixed(2);
        } else {
            price = this.props.price;
        }

        return (
            <a href="javascript:void(0);" onClick={this.props.onBtnAddToCartClick} className={class_name}><span className="value">{this.props.currency_symbol} {price}</span><span className="gap">
                <span className="separator"></span></span>{this.props.txtNextButton}</a>
        )
    }

});

module.exports = EuroMillionsAddToCart;