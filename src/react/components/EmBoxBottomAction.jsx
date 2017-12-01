var React = require('react');

var EuroMillionsAdvancedPlayBtn = require('./EmAdvancedPlayBtn.jsx');
var EuroMillionsAddToCart = require('./EmAddToCart.jsx');

var EuroMillionsBoxBottomAction = React.createClass({

    addToCart : function () {
        if(openTicket) {
            showModalTicketClose();
            return false;
        }
        var params = '';
        this.props.lines.forEach(function(bet,i){
            if(bet.numbers.length == 5 && bet.stars.length == 2 ) {
                params += 'bet['+i+']='+ bet.numbers +","+ bet.stars + '&';
            }
        });

        var draw_days = this.props.play_days;
        var draw_day_play = this.props.draw_day_play;
        var frequency = this.props.duration;
        var start_draw = String(this.props.date_play).split('#')[0];

        params += 'draw_days='+draw_days+'&frequency='+frequency+'&start_draw='+start_draw+'&draw_day_play='+draw_day_play;
        ajaxFunctions.playCart(params);
    },


    render : function () {
        var elem = [];

        elem.push(<EuroMillionsAdvancedPlayBtn show={false} reset={this.props.reset} config_changed={this.props.config_changed} click_advanced_play={this.props.click_advanced_play} key="1"/>);
        elem.push(<EuroMillionsAddToCart currency_symbol={this.props.currency_symbol} price={this.props.price} txtNextButton={this.props.txtNextButton} onBtnAddToCartClick={this.addToCart} key="2"/>);
        return (
        <div className="cl">
            <div className="right">
                <span className="total-price-description">
                    {this.props.total_price_description}
                </span>
                <span className="description-before-price">
                    {this.props.description_before_price}
                </span>
                {elem}
            </div>
        </div>

        )
    }
});


module.exports = EuroMillionsBoxBottomAction;