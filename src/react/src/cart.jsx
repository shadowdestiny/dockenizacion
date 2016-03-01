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
            checked_wallet : this.props.checked_wallet,
            new_balance: 0,
            fund_value : 0,
            logicCart : null,
            show_fee_value : true,
            total : 0,
            pre_total : 0,
        }
    },

    componentDidMount : function()
    {
        var price = parseFloat(this.props.single_bet_price * this.state.playConfigList.bets.length );
        var wallet_balance = parseFloat(this.props.wallet_balance);
        var fee = this.props.price_below_fee;
        if(wallet_balance == 0 && price > parseFloat(fee)) {
            this.setState({ show_all_fee : false, checked_wallet : this.state.checked_wallet });
        }
        this.handleUpdatePrice();
    },

    statics: {
        getTotal: function(total) {
            return total;
        }
    },


    componentWillMount : function()
    {
        var price = parseFloat(this.props.single_bet_price * this.state.playConfigList.bets.length );
        if(parseFloat(this.props.wallet_balance) > price) {
            this.setState({ show_all_fee : false, checked_wallet : this.props.checked_wallet });
        }
        CurrencyFormat.symbol_position = this.props.symbol_position;
        CurrencyFormat.currency_symbol = this.props.currency_symbol;

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
        if(value) {
            $('.payment').hide();
            $('.box-bottom').show();
        }
        this.state.checked_wallet = value;
        this.handleUpdatePrice();
    },

    handlePreTotal : function (value)
    {
        this.state.pre_total = parseFloat(value * this.props.single_bet_price * this.state.playConfigList.bets.length).toFixed(2);
    },

    handleClickAdd : function (value)
    {
        if(value) {
            this.state.fund_value = 0;
            this.handleUpdatePrice();
        }
    },

    updatePriceWithCheckedWallet : function () {
        LogicCart.total = this.props.total;
        LogicCart.fee_limit = this.props.price_below_fee;
        Funds.funds_value = isNaN(this.state.fund_value) ? 0 : this.state.fund_value;
        Fee.fee_value = this.props.fee_charge;
        Wallet.total_balance = this.props.wallet_balance;
        Wallet.isChecked = true;
        LogicCart.payWithWallet();
        this.state.show_all_fee = LogicCart.show_all_fee;
        this.state.show_fee_text = LogicCart.show_fee_text;
        this.state.show_fee_value = LogicCart.show_fee_value;
        return LogicCart.total;
    },


    updatePriceWithoutWallet : function () {
        LogicCart.total = this.props.total;
        LogicCart.fee_limit = this.props.price_below_fee;
        Funds.funds_value = isNaN(this.state.fund_value) ? 0 : this.state.fund_value;
        Fee.fee_value = this.props.fee_charge;
        Wallet.total_balance = this.props.wallet_balance;
        Wallet.isChecked = true;
        LogicCart.payWithNoWallet();
        this.state.show_all_fee = LogicCart.show_all_fee;
        this.state.show_fee_text = LogicCart.show_fee_text;
        this.state.show_fee_value = LogicCart.show_fee_value;
        return LogicCart.total;
    },

    handleUpdatePrice : function()
    {
        var price = parseFloat(this.props.total).toFixed(2);
        if(this.state.checked_wallet) {
            price = this.updatePriceWithCheckedWallet();
            this.state.new_balance = Wallet.getNewWalletBalance();
        } else {
            price = this.updatePriceWithoutWallet();
            this.state.new_balance = parseFloat(this.props.wallet_balance);
        }
        $(document).trigger("totalPriceEvent", [ parseFloat(price).toFixed(2) ]);
        CurrencyFormat.value = price;
        var price_and_symbol = CurrencyFormat.getCurrencyFormatted();
        this.setState({ total : price_and_symbol });
    },

    render : function ()
    {
        var _playConfigList = this.state.playConfigList;
        var _euroMillionsLine = [];
        var class_button_payment = 'btn blue big buy';

        CurrencyFormat.value = this.props.single_bet_price;
        var price_and_symbol_order_line = CurrencyFormat.getCurrencyFormatted();

        for (let i=0; i< _playConfigList.bets.length; i++) {
            var numbers = _playConfigList.bets[i].regular;
            var stars = _playConfigList.bets[i].lucky;

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
                                                callbackHandleClickAdd={this.handleClickAdd}
                                                show_fee_value={this.state.show_fee_value}
                                                currency_symbol={this.props.currency_symbol}
                                                show_all_fee={this.state.show_all_fee}
                                                show_fee_text={this.state.show_fee_text}
                                                keyup={this.handleKeyUpAddFund}
            />;

        var txt_button_payment = '';
        var href_payment = '';
        var data_btn = '';
        if( (this.state.checked_wallet && parseFloat(LogicCart.total) > 0) || parseFloat(LogicCart.total) > 0 ) {
            txt_button_payment = 'Continue to payment';
            href_payment = 'javascript:void(0)';
            data_btn = 'no-wallet';
        } else {
            txt_button_payment = 'Buy now';
            href_payment = '/cart/payment?method=wallet&charge='+this.state.fund_value;
            data_btn = 'wallet';
        }
        CurrencyFormat.value = this.state.pre_total;
        var pre_total_symbol = CurrencyFormat.getCurrencyFormatted();
        CurrencyFormat.value = this.props.wallet_balance;
        var symbol_price_balance = CurrencyFormat.getCurrencyFormatted();
        CurrencyFormat.value = this.state.new_balance;
        var symbol_price_new_balance = CurrencyFormat.getCurrencyFormatted();
        var old_balance_and_new_balance = <span className="value"> <span className='old'>{symbol_price_balance}</span><span className='new'>{symbol_price_new_balance}</span> </span>;

        if(!this.state.checked_wallet) {
            CurrencyFormat.value = this.state.new_balance;
            symbol_price_balance = CurrencyFormat.getCurrencyFormatted();
            old_balance_and_new_balance = <span className="value"> <span className="current">{symbol_price_balance}</span> </span>;
        }
        var wallet_component = null;
        if(parseFloat(this.props.wallet_balance) > 0) {
            var total_default = parseFloat(Wallet.wallet).toFixed(2)
            wallet_component = <EmWallet currency_symbol={this.props.currency_symbol}
                                         symbol_position={this.props.symbol_position}
                                         checked_callback={this.handleCheckedWallet}
                                         show_checked={this.state.checked_wallet}
                                         total_price={total_default}
                                         old_new_balance={old_balance_and_new_balance}
                                         wallet_balance={parseFloat(this.props.wallet_balance).toFixed(2)}
            />;
        }


        return (
            <div>
                <div className="box-top cl">
                    <h2 className="h4 sub-txt">Order Summary</h2>
                </div>
                <div className="box-order">
                    {_euroMillionsLine}
                    <EmLineOrderConfig config={this.props.config} playConfig={_playConfigList} pre_total={this.handlePreTotal} duration={this.handleChangeDrawDuration}/>
                    <div className="pre-total cl">
                        <div className="total">
                            <div className="txt">
                                Total
                            </div>
                            <div className="val">
                                 {pre_total_symbol}
                            </div>
                        </div>
                    </div>
                    {line_fee_component}
                    {wallet_component}
                </div>
                <EmTotalCart total_price={this.state.total} />
                <div className="box-bottom cl">
                    <a href={href_payment} data-btn={data_btn} className={class_button_payment}>{txt_button_payment}</a>
                </div>
            </div>
        )
    }
});

module.exports = CartPage;
var show_fee_line = false;


var CurrencyFormat = {

    symbol_position : 0,
    value : 0,
    currency_symbol : '',

    getCurrencyFormatted : function() {
        return CurrencyFormat.symbol_position ? parseFloat(CurrencyFormat.value).toFixed(2) + ' ' + CurrencyFormat.currency_symbol : CurrencyFormat.currency_symbol + ' ' + parseFloat(CurrencyFormat.value).toFixed(2);
    }
};



var LogicCart = {
    total : 0,
    fee_limit : 0,
    isPayWithWallet : false,
    fundsValue : 0,
    show_all_fee : false,
    show_fee_value : false,
    show_fee_text : false,

    payWithWallet : function () {
        var totalWithWallet = Wallet.getTotalWhenPayed(LogicCart.total);
        if( parseFloat(LogicCart.fee_limit) > parseFloat(totalWithWallet) ) {
            LogicCart.total = Funds.getTotalWhenFundsAreInserted(totalWithWallet);
            if( parseFloat(LogicCart.total).toFixed(2) < parseFloat(LogicCart.fee_limit).toFixed(2) ) {
                LogicCart.total = Fee.checkFeeWithWallet(LogicCart.total);
                if(Fee.applied) {
                    LogicCart.show_fee_text = true;
                    LogicCart.show_fee_value = true;
                    LogicCart.show_all_fee = true;
                } else {
                    LogicCart.show_fee_text = false;
                    LogicCart.show_fee_value = false;
                    LogicCart.show_all_fee = true;
                }
            } else {
                LogicCart.show_all_fee = true;
                LogicCart.show_fee_value = false;
                LogicCart.show_fee_text = false;
            }
        } else {
            LogicCart.total = Wallet.getTotalWhenPayed(LogicCart.total);
        }
    },

    payWithNoWallet : function () {
        if( parseFloat(LogicCart.fee_limit) > parseFloat(LogicCart.total) ) {
            LogicCart.total = Funds.getTotalWhenFundsAreInserted(LogicCart.total);
            if( parseFloat(LogicCart.total) < parseFloat(LogicCart.fee_limit) ) {
                LogicCart.total = Fee.getTotalWithFee(LogicCart.total);
                LogicCart.show_all_fee = true;
                LogicCart.show_fee_value = true;
                LogicCart.show_fee_text = true;
            } else {
                LogicCart.show_all_fee = true;
                LogicCart.show_fee_value = false;
                LogicCart.show_fee_text = false;
            }
        }
    }
};

var Wallet = {
    total_balance : 0,
    isChecked : false,
    wallet : 0,

    getTotalWhenPayed : function (total) {
        Wallet.wallet = parseFloat(Wallet.total_balance) > parseFloat(total) ? total : Wallet.total_balance;
        if( parseFloat(Wallet.wallet) >= parseFloat(total) ) {
            return ((parseFloat(Wallet.wallet) - parseFloat(total))).toFixed(2);
        } else {
            return ((parseFloat(total) - parseFloat(Wallet.wallet)).toFixed(2)) ;
        }
    },

    getNewWalletBalance : function () {
        return (parseFloat(Wallet.total_balance) - parseFloat(Wallet.wallet)).toFixed(2);
    }

};

var Funds = {
    funds_value : 0,
    getTotalWhenFundsAreInserted : function(value) {
        if ( parseInt(Funds.funds_value) > 0 ) {
            return (parseFloat(Funds.funds_value) + parseFloat(value)).toFixed(2);
        }
        return value;
    },

    getTotalPayWalletWithFunds : function () {
        return (parseFloat(Funds.funds_value) + parseFloat(Wallet.wallet)).toFixed(2);
    }
};

var Fee = {
    fee_value : 0,
    applied : true,

    checkFeeWithWallet : function (value) {
        if( parseFloat(value) >= parseFloat(LogicCart.fee_limit) ) {
            Fee.applied = false;
            return parseFloat(value).toFixed(2);
        } else {
            Fee.applied = true;
            return Fee.getTotalWithFee(value);
        }
    },

    getTotalWithFee : function(value) {
        return ((parseFloat(Fee.fee_value) + parseFloat(value)).toFixed(2));
    }
};


console.log('total price ' + total_price);

ReactDOM.render(<CartPage total={total_price} config={config} checked_wallet={checked_wallet} symbol_position={symbol_position} draw_days={draw_days} price_below_fee={price_below_fee} fee_charge={fee_charge} currency_symbol={currency_symbol} play_list={play_list} wallet_balance={wallet_balance} single_bet_price={single_bet_price} show_fee_line={show_fee_line}/>,
    document.getElementById('cart-order'));




