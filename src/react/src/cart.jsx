var React = require('react');
var ReactDOM = require('react-dom');
var EmLineOrderCart = require('../components/cart/EmLineOrderCart.jsx');
var EmLineOrderConfig = require('../components/cart/EmLineOrderConfig.jsx');
var EmTotalCart = require('../components/cart/EmTotalCart.jsx');
var EmLineFeeCart = require('../components/cart/EmLineFeeCart.jsx');
var EmWallet = require('../components/cart/EmWallet.jsx');

var CartPage = new React.createClass({

    displayName: 'CartPage',

    getInitialState : function ()
    {
        return {
            playConfigList : JSON.parse(this.props.play_list),
            show_fee_text : true,
            show_all_fee : false,
            checked_wallet : true,
            total : this.props.total_price
        }
    },

    componentDidMount : function()
    {
       this.setState({ show_all_fee : false });
       this.handleUpdatePrice();
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

    handleKeyUpAddFund : function (value)
    {
        if(parseFloat(value) > parseFloat(this.props.total_price)) {
            this.setState( {show_fee_text : false });
        } else {
            this.setState( {show_fee_text : true });
        }
    },

    handleCheckedWallet : function (value)
    {
        this.state.checked_wallet = value;
        this.handleUpdatePrice();
    },

    handleUpdatePrice : function()
    {
        var price = parseFloat(this.state.total);
        if(this.state.checked_wallet){
            var wallet = parseFloat(this.props.wallet_balance) > price ? price : this.props.wallet_balance;
            price = (wallet > price) ? wallet - price : price - wallet;
        } else {
            price = this.props.total_price;
        }

        //if(price == 0) {
        //    this.state.show_all_fee = false;
        //} else {
        //    this.state.show_all_fee = true;
        //}
        if(!this.state.checked_wallet) {
            this.state.show_all_fee = true;
        } else {
            this.state.show_all_fee = false;
        }
        this.setState({ total : price });
    },

    render : function ()
    {
        var _playConfigList = this.state.playConfigList;
        var _euroMillionsLine = [];
        for (let i=0; i< _playConfigList.euroMillionsLines.bets.length; i++) {
            var numbers = _playConfigList.euroMillionsLines.bets[i].regular;
            var stars = _playConfigList.euroMillionsLines.bets[i].lucky;
            _euroMillionsLine.push(<EmLineOrderCart currency_symbol={this.props.currency_symbol} line={i} key={i} numbers={numbers} stars={stars} single_bet_price={this.props.single_bet_price}/>);
        }
        var line_fee_component = null;
       //if(this.props.show_fee_line) {
            line_fee_component = <EmLineFeeCart currency_symbol={this.props.currency_symbol} show_all_fee={this.state.show_all_fee} show_fee_text={this.state.show_fee_text} keyup={this.handleKeyUpAddFund} />
       // }
        var wallet_component = null;
        if(parseFloat(this.props.wallet_balance) > 0) {
            wallet_component = <EmWallet currency_symbol={this.props.currency_symbol} checked_callback={this.handleCheckedWallet} show_checked={this.state.checked_wallet} total_price={this.props.total_price} wallet_balance={this.props.wallet_balance}/>;
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
                <div className="box-wallet">
                    {wallet_component}
                </div>
                <div className="box-total cl">
                    <EmTotalCart currency_symbol={this.props.currency_symbol} total_price={this.state.total} />
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
    total_price = parseFloat(total_price) + parseFloat(0.35); //EMTD 0.35 should be calculated inside react
}
ReactDOM.render(<CartPage currency_symbol={currency_symbol} play_list={play_list} wallet_balance={wallet_balance} single_bet_price={single_bet_price} total_price={total_price.toFixed(2)} show_fee_line={show_fee_line}/>,
    document.getElementById('cart-order'));
