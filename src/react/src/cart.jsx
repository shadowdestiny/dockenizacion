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
            checked_wallet : false,
            new_balance: 0,
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
        if(wallet_balance == 0 && price > parseFloat(fee)) {
            this.setState({ show_all_fee : false, checked_wallet : false });
        }
       this.handleUpdatePrice();
    },

    componentWillMount : function()
    {
        var price = parseFloat(this.props.single_bet_price * this.state.playConfigList.euroMillionsLines.bets.length );
        if(parseFloat(this.props.wallet_balance) > price) {
            this.setState({ show_all_fee : false, checked_wallet : true });
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


    isNumber : function(value) {
        return typeof value === 'number' && isFinite(value) && !isNaN(value)
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

    formatPriceWithSymbol : function (symbol, value)
    {
        return symbol ? parseFloat(value).toFixed(2) + ' ' + symbol : symbol + ' ' + parseFloat(value).toFixed(2);
    },

    handleCheckedWallet : function (value)
    {
        this.state.checked_wallet = value;
        this.handleUpdatePrice();
    },

    updatePriceWithCheckedWallet : function (price) {
        this.state.show_all_fee = false;
        var wallet = parseFloat(this.props.wallet_balance) > price ? price : this.props.wallet_balance;
        //Discomment if we want add fee when checked wallet
        //price = parseFloat(price) + parseFloat(fee);
        price = (parseFloat(wallet) > parseFloat(price)) ? wallet - price : price - wallet;
        this.state.new_balance = parseFloat(parseFloat(this.props.wallet_balance) - parseFloat(wallet)).toFixed(2);
        return price;
    },

    updatePriceWithoutWallet : function (price, wallet_balance, fee_below, fee) {

        //price less than fee_below_value and wallet_balance less than price
        if(parseFloat(price) < parseFloat(fee_below) /*&& parseFloat(wallet_balance) < parseFloat(price)*/) {
            price = parseFloat(price);
            this.state.show_all_fee = true;
            if(this.state.fund_value > 0) {
                if(parseFloat(price) + parseFloat(this.state.fund_value) < parseFloat(fee_below)) {
                    price = parseFloat(price) + parseFloat(this.state.fund_value) + parseFloat(fee);
                    this.state.show_all_fee = true;
                    this.state.show_fee_value = true;
                    this.state.show_fee_text = true;
                } else {
                    price = parseFloat(price) + parseFloat(this.state.fund_value);
                    this.state.show_all_fee = true;
                    this.state.show_fee_value = false;
                    this.state.show_fee_text = false;
                }
            } else {
                this.state.show_all_fee = true;
                this.state.show_fee_value = true;
                this.state.show_fee_text = true;
                price = price + parseFloat(fee);
            }
        } else if(parseFloat(wallet_balance) > parseFloat(price)) {
            this.state.show_fee_value = true;
            this.state.show_fee_text = true;
            this.state.show_all_fee = true;
            price = parseFloat(price) + parseFloat(fee);
        } else if(parseFloat(price) > parseFloat(fee_below)) {
            this.state.show_fee_value = false;
            this.state.show_fee_text = false;
            this.state.show_all_fee = false;
            var fund_value = (this.state.fund_value > 0.00) ? parseFloat(this.state.fund_value) : 0.00;
            price = parseFloat(price) + parseFloat(fund_value);
        }
        return price;

    },

    handleUpdatePrice : function()
    {
        var price = parseFloat(this.props.total).toFixed(2);
        var wallet_balance = parseFloat(this.props.wallet_balance).toFixed(2);
        var fee_below = parseFloat(this.props.price_below_fee).toFixed(2);
        var fee = parseFloat(this.props.fee_charge).toFixed(2);

        if(this.state.checked_wallet) {
            price = this.updatePriceWithCheckedWallet(price);
        } else {
            price = this.updatePriceWithoutWallet(price,wallet_balance,fee_below,fee);
            this.state.new_balance = parseFloat(this.props.wallet_balance);
        }
        var price_and_symbol = this.props.symbol_position ? parseFloat(price).toFixed(2) + ' ' + this.props.currency_symbol : this.props.currency_symbol + ' ' + parseFloat(price).toFixed(2);
        this.setState({ total : price_and_symbol });
    },

    render : function ()
    {
        var _playConfigList = this.state.playConfigList;
        var _euroMillionsLine = [];

        var price_and_symbol_order_line = this.props.symbol_position ? (this.props.single_bet_price * this.state.playConfigList.drawDays).toFixed(2) + ' ' + this.props.currency_symbol : this.props.currency_symbol + ' ' + (this.props.single_bet_price * this.state.playConfigList.drawDays).toFixed(2);

        for (let i=0; i< _playConfigList.euroMillionsLines.bets.length; i++) {
            var numbers = _playConfigList.euroMillionsLines.bets[i].regular;
            var stars = _playConfigList.euroMillionsLines.bets[i].lucky;
            _euroMillionsLine.push(<EmLineOrderCart
                                                    line={i}
                                                    key={i}
                                                    numbers={numbers}
                                                    stars={stars}
                                                    single_bet_price={price_and_symbol_order_line}
                />);
        }

        var line_fee_component = <EmLineFeeCart
                                                symbol_position={this.props.symbol_position}
                                                fee_charge={this.props.fee_charge}
                                                price_below_fee={this.props.price_below_fee}
                                                show_fee_value={this.state.show_fee_value}
                                                currency_symbol={this.props.currency_symbol}
                                                show_all_fee={this.state.show_all_fee}
                                                show_fee_text={this.state.show_fee_text}
                                                keyup={this.handleKeyUpAddFund}
            />

        var wallet_component = null;

        if(parseFloat(this.props.wallet_balance) > 0) {
            var total_default = parseFloat(this.props.total).toFixed(2);

            //add fee if total less than price below fee
            //if(parseFloat(total_default) < parseFloat(this.props.price_below_fee)){
              //  total_default = parseFloat(total_default) + parseFloat(this.props.fee_charge);
            // }
            wallet_component = <EmWallet currency_symbol={this.props.currency_symbol}
                                         checked_callback={this.handleCheckedWallet}
                                         show_checked={this.state.checked_wallet}
                                         total_price={total_default}
                                         wallet_balance={parseFloat(this.props.wallet_balance).toFixed(2)}
                />;
        }
        var txt_button_payment = this.state.checked_wallet ? 'Buy now' : 'Continue to payment';
        var symbol_price_balance = this.props.symbol_position ? this.props.wallet_balance + ' ' + this.props.currency_symbol : this.props.currency_symbol + ' ' + this.props.wallet_balance;
        var old_balance_and_new_balance = <span className="value"> <span className='old'>{symbol_price_balance}</span><span className='new'>{this.props.currency_symbol} {this.state.new_balance}</span> </span>;
        if(!this.state.checked_wallet) {
            symbol_price_balance = this.props.symbol_position ? this.state.new_balance + ' ' + this.props.currency_symbol : this.props.currency_symbol + ' ' + this.state.new_balance;
            old_balance_and_new_balance = <span className="value"> <span className="current">{symbol_price_balance}</span> </span>;
        }

        return (
            <div>
                <div className="box-top cl">
                    <div className="balance">
                        <span className="txt">Your current wallet balance:</span>
                        {old_balance_and_new_balance}
                    </div>
                    <h2 className="h4 sub-txt">Order Summary</h2>
                </div>
                <div className="box-order">
                    <EmLineOrderConfig playConfig={_playConfigList} duration={this.handleChangeDrawDuration}/>
                    {_euroMillionsLine}
                    {line_fee_component}
                    {wallet_component}
                </div>
                <EmTotalCart total_price={this.state.total} />
                <div className="box-bottom cl">
                    <a href="javascript:void(0)" className="btn blue big buy">{txt_button_payment}</a>
                </div>
            </div>
        )
    }
});

module.exports = CartPage;
var show_fee_line = false;
ReactDOM.render(<CartPage total={total_price} symbol_position={symbol_position} price_below_fee={price_below_fee} fee_charge={fee_charge} currency_symbol={currency_symbol} play_list={play_list} wallet_balance={wallet_balance} single_bet_price={single_bet_price} show_fee_line={show_fee_line}/>,
    document.getElementById('cart-order'));


