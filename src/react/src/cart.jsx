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
            show_fee_value : true,
            total : 0,
            pre_total : 0
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

    updatePriceWithCheckedWallet : function (price) {
        this.state.show_all_fee = false;
        var wallet = parseFloat(this.props.wallet_balance) > price ? price : this.props.wallet_balance;
        var fund_value = (this.state.fund_value > 0.00) ? parseFloat(this.state.fund_value) : 0.00;
        price = (parseFloat(wallet) > parseFloat(price)) ? wallet - price : price - wallet;

        if(parseFloat(this.props.wallet_balance) < parseFloat(this.props.price_below_fee)) {
            this.state.show_all_fee = true;
            this.state.show_fee_value = true;
            this.state.show_fee_text = true;
            if(parseFloat(price) + parseFloat(fund_value) > parseFloat(this.props.price_below_fee).toFixed(2)) {
                price = parseFloat(price) + parseFloat(fund_value);
                this.state.show_fee_text = false;
            } else {
                price = parseFloat(price) + parseFloat(this.props.fee_charge) + parseFloat(fund_value);
            }
        }
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
            this.state.show_fee_value = false;
            this.state.show_fee_text = false;
            this.state.show_all_fee = false;
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
        $(document).trigger("totalPriceEvent", [ parseFloat(price).toFixed(2) ]);
        var price_and_symbol = this.props.symbol_position ? parseFloat(price).toFixed(2) + ' ' + this.props.currency_symbol : this.props.currency_symbol + ' ' + parseFloat(price).toFixed(2);
        this.setState({ total : price_and_symbol });
    },

    render : function ()
    {
        var _playConfigList = this.state.playConfigList;
        var _euroMillionsLine = [];
        var class_button_payment = 'btn blue big buy';
        //EMTD
        var price_and_symbol_order_line = this.props.symbol_position ? parseFloat(this.props.single_bet_price).toFixed(2) + ' ' + this.props.currency_symbol : this.props.currency_symbol + ' ' + parseFloat(this.props.single_bet_price).toFixed(2);

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

        var wallet_component = null;

        if(parseFloat(this.props.wallet_balance) > 0) {
            var total_default = parseFloat(this.props.total).toFixed(2);
            wallet_component = <EmWallet currency_symbol={this.props.currency_symbol}
                                         symbol_position={this.props.symbol_position}
                                         checked_callback={this.handleCheckedWallet}
                                         show_checked={this.state.checked_wallet}
                                         total_price={total_default}
                                         wallet_balance={parseFloat(this.props.wallet_balance).toFixed(2)}
                />;
        }
        var txt_button_payment = '';
        var href_payment = '';
        var data_btn = '';

        if(this.state.checked_wallet && ( parseInt(this.props.wallet_balance) > parseInt(this.props.total) ) ) {
            txt_button_payment = 'Buy now';
            href_payment = '/cart/payment?method=wallet&charge='+this.state.fund_value;
            data_btn = 'wallet';
        } else {
            txt_button_payment = 'Continue to payment';
            href_payment = 'javascript:void(0)';
            data_btn = 'no-wallet';
        }

        var pre_total_symbol = this.props.symbol_position ? this.state.pre_total + ' ' + this.props.currency_symbol : this.props.currency_symbol + ' ' + this.state.pre_total;

        //EMTD when we have more time, change this vars with var received from server.
        var symbol_price_balance = this.props.symbol_position ? this.props.wallet_balance + ' ' + this.props.currency_symbol : this.props.currency_symbol + ' ' + this.props.wallet_balance;
        var symbol_price_new_balance = this.props.symbol_position ? this.state.new_balance + ' ' + this.props.currency_symbol : this.props.currency_symbol + ' ' + this.state.new_balance;
        var old_balance_and_new_balance = <span className="value"> <span className='old'>{symbol_price_balance}</span><span className='new'>{symbol_price_new_balance}</span> </span>;
        if(!this.state.checked_wallet) {
            symbol_price_balance = this.props.symbol_position ? this.state.new_balance + ' ' + this.props.currency_symbol : this.props.currency_symbol + ' ' + this.state.new_balance;
            old_balance_and_new_balance = <span className="value"> <span className="current">{symbol_price_balance}</span> </span>;
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
                    <div className="balance">
                        <span className="txt">Balance:</span>
                        {old_balance_and_new_balance}
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


ReactDOM.render(<CartPage total={total_price} config={config} checked_wallet={checked_wallet} symbol_position={symbol_position} draw_days={draw_days} price_below_fee={price_below_fee} fee_charge={fee_charge} currency_symbol={currency_symbol} play_list={play_list} wallet_balance={wallet_balance} single_bet_price={single_bet_price} show_fee_line={show_fee_line}/>,
    document.getElementById('cart-order'));


