var React = require('react');

var EuroMillionsAddToCart = new React.createClass({

    propTypes: {
        onBtnAddToCartClick : React.PropTypes.func.isRequired
    },
    render : function () {

        var class_name = 'btn add-cart';
        if(this.props.price > 0) class_name = class_name + ' active';

        return (
            <a href="javascript:void(0);" onClick={this.props.onBtnAddToCartClick} className={class_name}><span className="value">{this.props.currency_symbol} {this.props.price}</span><span className="gap">
                <span className="separator"></span></span>{this.props.txtNextButton}</a>
        )
    }

});

module.exports = EuroMillionsAddToCart;