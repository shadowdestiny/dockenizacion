var React = require('react');
var ReactDOM = require('react-dom');
var EmLineOrderCart = require('../components/cart/EmLineOrderCart.jsx');
var EmLineOrderConfig = require('../components/cart/EmLineOrderConfig.jsx');
var EmTotalCart = require('../components/cart/EmTotalCart.jsx');
var EmLineFeeCart = require('../components/cart/EmLineFeeCart.jsx');

var CartPage = new React.createClass({

    displayName: 'CartPage',

    getInitialState : function ()
    {
        return {
            playConfigList : JSON.parse(this.props.play_list)
        }
    },

    handleChangeDrawDuration : function (value)
    {
        var params = '';
        this.state.playConfigList.euroMillionsLines.bets.forEach(function(bet,i){
             params += 'bet['+i+']='+bet.regular+","+bet.lucky +'&';
        });
        var draw_days = this.state.playConfigList.drawDays;
        var frequency = value;
        var start_draw = this.state.playConfigList.startDrawDate;
        params += 'draw_days='+draw_days+'&frequency='+frequency+'&start_draw='+start_draw;
        globalFunctions.playCart(params);
    },

    render : function ()
    {
        var _playConfigList = this.state.playConfigList;
        var _euroMillionsLine = [];
        for (let i=0; i< _playConfigList.euroMillionsLines.bets.length; i++) {
            var numbers = _playConfigList.euroMillionsLines.bets[i].regular;
            var stars = _playConfigList.euroMillionsLines.bets[i].lucky;
            _euroMillionsLine.push(<EmLineOrderCart line={i} key={i} numbers={numbers} stars={stars} single_bet_price={this.props.single_bet_price}/>);
        }
        var line_fee_component = null;
        if(this.props.show_fee_line) {
            line_fee_component = <EmLineFeeCart />
        }

        return (
            <div>
                <div className="box-order">
                    <EmLineOrderConfig playConfig={_playConfigList} duration={this.handleChangeDrawDuration}/>
                    {_euroMillionsLine}
                </div>
                <div className="box-order">
                    {line_fee_component}
                </div>
                <div className="box-total cl">
                    <EmTotalCart total_price={this.props.total_price} />
                </div>
            </div>
        )

    }
});

module.exports = CartPage;

//EMTD put this value as constant global in app
var price_below_fee = 12;
var show_fee_line = false;
if( total_price < price_below_fee) {
    show_fee_line = true;
    total_price = parseFloat(total_price) + parseFloat(0.35);
}
ReactDOM.render(<CartPage play_list={play_list} wallet_balance={wallet_balance} single_bet_price={single_bet_price} total_price={total_price.toFixed(2)} show_fee_line={show_fee_line}/>,
    document.getElementById('cart-order'));
