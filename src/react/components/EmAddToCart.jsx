var React = require('react');
import PropTypes from 'prop-types';
var createReactClass = require('create-react-class');
var EuroMillionsAddToCart = createReactClass({

    propTypes: {
        onBtnAddToCartClick : PropTypes.element.isRequired
    },
    render : function () {

        var class_name = 'btn add-cart';
        var price = 0;
        if(this.props.price > 0) class_name = class_name + ' active';
        if(this.props.enablePowerPlay) {
            price = parseFloat(this.props.price) + this.props.powerplay*(this.props.total_draws * this.props.total_lines);
            price = price.toFixed(2);
        } else {
            price = this.props.price;
        }

        return (
            <a href="javascript:void(0);" onClick={this.props.onBtnAddToCartClick} className={class_name}><span className="value">{this.props.currency_symbol} {price}</span><span className="gap">
                <span className="separator"></span></span><span>{this.props.txtNextButton}</span></a>
        )
    }

});

export default  EuroMillionsAddToCart;