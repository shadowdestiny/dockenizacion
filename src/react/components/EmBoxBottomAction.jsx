var React = require('react');

var EuroMillionsAdvancedPlayBtn = require('./EmAdvancedPlayBtn.jsx');
var EuroMillionsAddToCart = require('./EmAddToCart.jsx');

var EuroMillionsBoxBottomAction = React.createClass({

    addToCart : function () {
        var params = '';
        this.props.lines.forEach(function(bet,i){
            if(bet.numbers.length > 0 && bet.stars.length > 0 ) {
                params += 'bet['+i+']='+ bet.numbers +","+ bet.stars + '&';
            }
        });
        var draw_days = this.props.play_days;
        var frequency = this.props.duration;
        var start_draw = String(this.props.date_play).split('#')[0];


        params += 'draw_days='+draw_days+'&frequency='+frequency+'&start_draw='+start_draw;
        ajaxFunctions.playCart(params);
    },


    render : function () {
        var elem = [];
        elem.push(<EuroMillionsAdvancedPlayBtn click_advanced_play={this.props.click_advanced_play} key="1"/>);
        elem.push(<EuroMillionsAddToCart currency_symbol={this.props.currency_symbol} price={this.props.price} onBtnAddToCartClick={this.addToCart} key="2"/>);

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