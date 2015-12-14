var React = require('react');

var EuroMillionsAdvancedPlay = require('./EmAdvancedPlay.jsx');
var EuroMillionsAddToCart = require('./EmAddToCart.jsx');

var EuroMillionsBoxBottomAction = React.createClass({


    addToCart : function () {

    },

    render : function () {
        var elem = [];
        elem.push(<EuroMillionsAdvancedPlay key="1"/>);
        elem.push(<EuroMillionsAddToCart price={this.props.price} onBtnAddToCartClick={this.addToCart} key="2"/>);

        return (
        <div className="cl" id="box-action">
            <div className="right">
                {elem}
            </div>
        </div>

        )
    }
})


module.exports = EuroMillionsBoxBottomAction;