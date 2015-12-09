var React = require('react');

var EuroMillionsAddToCart = new React.createClass({

    propTypes: {
        onBtnAddToCartClick : React.PropTypes.func.isRequired,

    },
    render : function () {
        var class_name = 'btn add-cart'
        if(this.props.price > 0) class_name = class_name + ' active';

        return (
            <a href="javascript:void(0);" onClick={this.props.onBtnAddToCartClick.bind(null, null)} className={class_name}><span className="value">&euro; {this.props.price}</span><span className="gap">
                <span className="separator"></span></span>Add to Cart</a>
        )
    }

});

module.exports = EuroMillionsAddToCart;