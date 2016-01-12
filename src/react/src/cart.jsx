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
            fund_value : 0,
            show_fee_value : true,
            total : 0
        }
    },

    componentDidMount : function()
    {
        var price = parseFloat(this.props.single_bet_price * this.state.playConfigList.euroMillionsLines.bets.length );
        var wallet_balance = parseFloat(this.props.wallet_balance);
        var fee = this.props.price_below_fee;
        if( wallet_balance < price && price < parseFloat(fee)) {
            this.setState({ show_all_fee : true, checked_wallet : false});
        }
        if(wallet_balance == 0 && price > parseFloat(fee)) {
            this.setState({ show_all_fee : false, checked_wallet : false });
        }
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
        this.state.fund_value = parseFloat(value);
        if(parseFloat(value) > parseFloat(this.props.total_price)) {
            this.setState( {show_fee_text : false });
        } else {
          //  this.setState( {show_fee_text : true });
        }
        this.handleUpdatePrice();
    },

    handleCheckedWallet : function (value)
    {
        this.state.checked_wallet = value;
        this.handleUpdatePrice();
    },

    handleUpdatePrice : function()
    {
        var price = parseFloat(this.props.single_bet_price * this.state.playConfigList.euroMillionsLines.bets.length );
        var wallet_balance = parseFloat(this.props.wallet_balance);
        var fee = this.props.price_below_fee;


        if( wallet_balance < price && price < parseFloat(fee)) {
            this.state.checked_wallet = false;
            price = price + parseFloat(this.props.fee_charge);
            this.state.show_all_fee = true;
        }

        if(wallet_balance == 0 && price > parseFloat(fee)) {
            this.state.checked_wallet = false;
            this.setState({ show_all_fee : false});
        }

        if(this.state.checked_wallet){
            var wallet = parseFloat(this.props.wallet_balance) > price ? price : this.props.wallet_balance;
            price = (wallet > price) ? wallet - price : price - wallet;
            this.state.show_all_fee = false;
        } else {
            if(!this.state.show_all_fee) {
                price = price + parseFloat(this.props.fee_charge);
                this.state.show_all_fee = true;
            }
        }

        if(this.state.fund_value > this.props.price_below_fee) {
            price = price - parseFloat(this.props.fee_charge);
            this.state.show_fee_value = false;
            this.state.show_fee_text = false;
        } else {
            this.state.show_fee_value = true;
            this.state.show_fee_text = true;
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
        var line_fee_component = <EmLineFeeCart fee_charge={this.props.fee_charge} price_below_fee={this.props.price_below_fee} show_fee_value={this.state.show_fee_value} currency_symbol={this.props.currency_symbol} show_all_fee={this.state.show_all_fee} show_fee_text={this.state.show_fee_text} keyup={this.handleKeyUpAddFund} />
        var wallet_component = null;

        if(parseFloat(this.props.wallet_balance) > parseFloat(this.state.total)) {
            var total_default = parseFloat(this.props.single_bet_price * this.state.playConfigList.euroMillionsLines.bets.length );
            wallet_component = <EmWallet currency_symbol={this.props.currency_symbol} checked_callback={this.handleCheckedWallet} show_checked={this.state.checked_wallet} total_price={total_default} wallet_balance={this.props.wallet_balance}/>;
        }

        var txt_button_payment = this.state.checked_wallet ? 'Pay with my wallet balance' : 'Continue to payment';

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
                <div className="box-bottom cl">
                    <a href="javascript:void(0)" className="btn blue big buy">{txt_button_payment}</a>
                </div>
            </div>
        )

    }
});

module.exports = CartPage;

var show_fee_line = false;
//if( total_price < price_below_fee) {
//    show_fee_line = true;
//    total_price = parseFloat(total_price) + parseFloat(fee_charge); //EMTD 0.35 should be calculated inside react
//}
ReactDOM.render(<CartPage price_below_fee={price_below_fee} fee_charge={fee_charge} currency_symbol={currency_symbol} play_list={play_list} wallet_balance={wallet_balance} single_bet_price={single_bet_price} show_fee_line={show_fee_line}/>,
    document.getElementById('cart-order'));


