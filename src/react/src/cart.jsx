var React = require('react');
var ReactDOM = require('react-dom');
var EmLineOrderCart = require('../components/cart/EmLineOrderCart.jsx');
var EmLineOrderConfig = require('../components/cart/EmLineOrderConfig.jsx');
var EmResumeOrder = require('../components/cart/EmResumeOrder.jsx');
var EmTotalCart = require('../components/cart/EmTotalCart.jsx');
var EmAmountCart = require('../components/cart/EmAmountCart.jsx');
var EmLineFeeCart = require('../components/cart/EmLineFeeCart.jsx');
var EmWallet = require('../components/cart/EmWallet.jsx');
var EmBtnPayment = require('../components/cart/EmBtnPayment.jsx');
var EmCard = require('../components/payment/EmCard');
var EmOptionSelector = require('../components/payment/EmOptionSelector');

var _eurojackpot;
try{
    _eurojackpot = eurojackpot;
} catch (e) {
    _eurojackpot = false;
}
var _megasena;
try{
    _megasena = megasena;
} catch (e) {
    _megasena = false;
}

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
            mmxloading: false,
            show_payment_section:false,
            typePayment:1,
        }
    },

    componentDidMount : function()
    {
        var price = parseFloat(this.props.single_bet_price * this.state.playConfigList.bets.length );
        var wallet_balance = parseFloat(this.props.wallet_balance);
        var fee = this.props.price_below_fee;
        if( wallet_balance == 0 && accounting.unformat(price) > accounting.unformat(fee)) {
            this.setState({ show_all_fee : false, checked_wallet : this.state.checked_wallet });
        }
        //this.moneyMatrix();
        this.handleUpdatePrice();
    },

    statics: {
        getTotal: function(total) {
            return total;
        }
    },

    shouldComponentUpdate : function (nextProps, nextState)
    {
        if (nextState.mmxloading != this.state.mmxloading) return true;
        return true;
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
        Funds.funds_value = this.state.fund_value;
        this.handleUpdatePrice();
    },

    formatPriceWithSymbol : function (symbol, value)
    {
        return symbol ? parseFloat(value).toFixed(2) + ' ' + symbol : symbol + ' ' + parseFloat(value).toFixed(2);
    },



    disableCheckedWallet: function(value)
    {
        this.setState({mmxloading : value});
    },

    selectTypePayment : function(option) {
        this.setState({typePayment : option});
    },

    handleCheckedWallet : function (value)
    {
        var self = this;
        /*if(value) {
            $('.payment').hide();
            $('.box-bottom').show();
        }*/
        this.state.checked_wallet = value;

        $(document).on("disableiframeclick",{disabled: false},function(e, _object) {
            console.log(_object);
            self.disableCheckedWallet(_object.disabled);
            let data = JSON.parse(_object.data);
            if (data === null){
                self.setState({
                    show_payment_section : false
                });
            } else if(data.type === 'credit_card') {
                self.setState({
                    show_payment_section : !value
                });
            }

        });
        this.moneyMatrix();
        this.handleUpdatePrice();
    },

    moneyMatrix: function()
    {
        var total_lines = this.state.playConfigList.bets.length;
        var frequency = JSON.parse(this.props.config).frequency;
        var price = this.props.total;
        if(this.props.powerplay) {
            var total_powerprice = (parseFloat(this.props.powerplayprice) * total_lines) * frequency;
            price = parseFloat(price) + parseFloat(total_powerprice);
        }
        if(this.state.checked_wallet) {
            price = this.updatePriceWithCheckedWallet();
            this.state.new_balance = Wallet.getNewWalletBalance();

        } else {
            price = this.updatePriceWithoutWallet();
            this.state.new_balance = parseFloat(this.props.wallet_balance);
        }

        $(document).trigger("moneymatrix", [ this.state.checked_wallet ]);

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
        LogicCart.pre_total = this.state.pre_total;
        Funds.funds_value = isNaN(this.state.fund_value) ? 0 : this.state.fund_value;
        Fee.fee_value = this.props.fee_charge;
        Wallet.total_balance = this.props.wallet_balance;
        Wallet.isChecked = true;
        PowerPlayCart.numLines = this.state.playConfigList.bets.length;
        PowerPlayCart.PowerPlay = this.props.powerplay;
        PowerPlayCart.powerPlayPrice = this.props.powerplayprice;
        var frequency = JSON.parse(this.props.config).frequency;
        if (PowerPlayCart.PowerPlay === 1) {
            PowerPlayCart.total = (PowerPlayCart.numLines * PowerPlayCart.powerPlayPrice) * frequency;

        }
        LogicCart.total = parseFloat(LogicCart.total) + parseFloat(PowerPlayCart.total);

        LogicCart.pre_total = LogicCart.total;
        LogicCart.payWithWallet();
        this.state.show_all_fee = LogicCart.show_all_fee;
        this.state.show_fee_text = LogicCart.show_fee_text;
        this.state.show_fee_value = LogicCart.show_fee_value;
        this.state.pre_total = LogicCart.pre_total;
        return LogicCart.total;
    },


    updatePriceWithoutWallet : function () {
        LogicCart.total = this.props.total;
        LogicCart.fee_limit = this.props.price_below_fee;
        LogicCart.pre_total = this.state.pre_total;
        Funds.funds_value = isNaN(this.state.fund_value) ? 0 : this.state.fund_value;
        Fee.fee_value = this.props.fee_charge;
        Wallet.total_balance = this.props.wallet_balance;
        PowerPlayCart.numLines = this.state.playConfigList.bets.length;
        PowerPlayCart.PowerPlay = this.props.powerplay;
        PowerPlayCart.powerPlayPrice = this.props.powerplayprice;
        var frequency = JSON.parse(this.props.config).frequency;
        if (PowerPlayCart.PowerPlay === 1) {
            PowerPlayCart.total = (PowerPlayCart.numLines * PowerPlayCart.powerPlayPrice) * frequency;

        }
        LogicCart.total = parseFloat(LogicCart.total) + parseFloat(PowerPlayCart.total);

        LogicCart.pre_total = LogicCart.total;
        LogicCart.payWithNoWallet();
        this.state.show_all_fee = LogicCart.show_all_fee;
        this.state.show_fee_text = LogicCart.show_fee_text;
        this.state.show_fee_value = LogicCart.show_fee_value;
        this.state.pre_total = LogicCart.pre_total;
        return LogicCart.total;
    },

    handleUpdatePrice : function()
    {
        var total_lines = this.state.playConfigList.bets.length;
        var frequency = JSON.parse(this.props.config).frequency;
        var price = this.props.total;
        if(this.props.powerplay) {
            var total_powerprice = (parseFloat(this.props.powerplayprice) * total_lines) * frequency;
            price = parseFloat(price) + parseFloat(total_powerprice);

        }
        if(this.state.checked_wallet) {
            price = this.updatePriceWithCheckedWallet();
            this.state.new_balance = Wallet.getNewWalletBalance();

        } else {
            price = this.updatePriceWithoutWallet();
            this.state.new_balance = parseFloat(this.props.wallet_balance);
        }
        $(document).trigger("totalPriceEvent", [ parseFloat(price).toFixed(2), Funds.funds_value ]);
        CurrencyFormat.value = price;
        var price_and_symbol = CurrencyFormat.getCurrencyFormatted();
        this.setState({ total : price_and_symbol });
    },

    render : function ()
    {
        var _playConfigList = this.state.playConfigList;
        var _euroMillionsLine = [];
        var class_button_payment = 'btn blue big buy';
        var single_bet = 0;
        if(this.props.powerplay) {
            single_bet = parseFloat(this.props.single_bet_price) + parseFloat(this.props.powerplayprice);
        } else {
            single_bet = this.props.single_bet_price;
        }

        let total_to_pay_formated =  CurrencyFormat.getCurrencyFormattedFromValue(single_bet * _playConfigList.bets.length);

        CurrencyFormat.value = single_bet;

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
                txt_line={this.props.txt_line}
                powerplay={this.props.powerplay}
                powerplayprice={this.props.powerplayprice}
                powerball={this.props.powerball}
                megamillions={this.props.megamillions}
                eurojackpot={this.props.eurojackpot}
                megasena={this.props.megasena}
                playingPP={this.props.playingPP}
                playingMM={this.props.playingMM}

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
            txt_checkout_fee={txt_checkout_fee}
        />;

        var txt_button_payment = '';
        var href_payment = '';
        var data_btn = '';
        var price_txt_btn = '';
        if( (this.state.checked_wallet && accounting.unformat(LogicCart.total) > 0) || accounting.unformat(LogicCart.total) > 0 ) {
            txt_button_payment = this.props.txt_gotopay_btn;
            price_txt_btn = this.state.total;
            href_payment = 'javascript:void(0)';
            data_btn = 'no-wallet';
        } else if(powerball === true) {
            txt_button_payment = this.props.txt_buy_btn;
            href_payment = '/powerball/payment/payment?method=wallet&charge=' + this.state.fund_value;
            data_btn = 'wallet';
            price_txt_btn = this.state.total;
        } else if(megamillions === true) {
                txt_button_payment = this.props.txt_buy_btn;
                href_payment = '/megamillions/payment/payment?method=wallet&charge='+this.state.fund_value;
                data_btn = 'wallet';
                price_txt_btn = this.state.total;
        } else if(_eurojackpot === true) {
            txt_button_payment = this.props.txt_buy_btn;
            href_payment = '/eurojackpot/payment/payment?method=wallet&charge='+this.state.fund_value;
            data_btn = 'wallet';
            price_txt_btn = this.state.total;
        } else if(_megasena === true) {
            txt_button_payment = this.props.txt_buy_btn;
            href_payment = '/megasena/payment/payment?method=wallet&charge='+this.state.fund_value;
            data_btn = 'wallet';
            price_txt_btn = this.state.total;
        } else {
            txt_button_payment = this.props.txt_buy_btn;
            href_payment = '/euromillions/payment/payment?method=wallet&charge='+this.state.fund_value;
            data_btn = 'wallet';
            price_txt_btn = this.state.total;
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
        var total_lines = _playConfigList.bets.length;
        if(parseFloat(this.props.wallet_balance) > 0) {
            wallet_component = <EmWallet currency_symbol={this.props.currency_symbol}
                                         symbol_position={this.props.symbol_position}
                                         checked_callback={this.handleCheckedWallet}
                                         show_checked={this.state.checked_wallet}
                                         total_price={this.props.total}
                                         old_new_balance={old_balance_and_new_balance}
                                         wallet_balance={parseFloat(this.props.wallet_balance).toFixed(2)}
                                         txt_payWithBalanceOption={this.props.txt_payWithBalanceOption}
                                         powerplay={this.props.powerplay}
                                         moneymatrixiframeloading={this.state.mmxloading}
                                         total_lines={total_lines}
                                         powerplayprice={this.props.powerplayprice}
                                         config={JSON.parse(this.props.config)}
                                         each_price={price_and_symbol_order_line}
            />;
        }

        return (
            <div>
                <EmResumeOrder draw_days={this.props.draw_days} href={href_payment}
                               databtn={data_btn} price={price_txt_btn} classBtn={class_button_payment}
                               text={txt_button_payment} config={this.props.config} playConfig={_playConfigList}
                               pre_total={this.handlePreTotal} duration={this.handleChangeDrawDuration}
                               pricetopay={this.state.total} funds={Funds.funds_value}
                               total_price={this.state.total} txt_summary={this.props.txt_summary}
                               txt_draws={this.props.txt_draws} txt_on={this.props.txt_on} txt_total={this.props.txt_total}
                               txt_edit={this.props.txt_edit} txt_link_play={this.props.txt_link_play}
                               txt_link_powerball={this.props.txt_link_powerball}
                               txt_link_megamillions={this.props.txt_link_megamillions}
                               txt_link_eurojackpot={this.props.txt_link_eurojackpot}
                               txt_line={this.props.txt_line}
                               tuesday={this.props.tuesday}
                               friday={this.props.friday}
                               wednesday={this.props.wednesday}
                               saturday={this.props.saturday}
                               currency_symbol={this.props.currency_symbol}
                               txt_for={this.props.txt_for}
                               txt_since={this.props.txt_since}
                               txt_weeks={this.props.txt_weeks}
                               txt_lottery={this.props.txt_lottery}
                               txt_lottery_title={this.props.txt_lottery_title}
                               txt_number_of_draws={this.props.txt_number_of_draws}
                               txt_starting_date={this.props.txt_starting_date}
                               txt_ending_date={this.props.txt_ending_date}
                />

                <div className={'box-order'}>
                    {_euroMillionsLine}
                    <br />
                    <EmAmountCart pricetopay={total_to_pay_formated} txt_amount={this.props.txt_amount}/>
                    {wallet_component}
                    <EmLineOrderConfig config={this.props.config} playConfig={_playConfigList}
                                       pre_total={this.handlePreTotal} duration={this.handleChangeDrawDuration} wednesday={this.props.wednesday}
                                       saturday={this.props.saturday} txt_for={this.props.txt_for}
                                       txt_since={this.props.txt_since}
                                       txt_weeks={this.props.txt_weeks}
                                       txt_lottery={this.props.txt_lottery}/>
                    {line_fee_component}
                </div>
                <EmTotalCart pricetopay={this.state.total} funds={Funds.funds_value} total_price={this.state.total}
                             txt_currencyAlert={this.props.txt_currencyAlert} txt_total={this.props.txt_total}
                             txtMultTotalPrice={this.props.txtMultTotalPrice}
                             emBtnPayment={<EmBtnPayment href={href_payment} databtn={data_btn} price={price_txt_btn}
                                                         classBtn={class_button_payment} text={txt_button_payment} powerplay={this.props.powerplay}
                                                         total_lines={total_lines}
                                                         powerplayprice={this.props.powerplayprice}
                                                         moneymatrixiframeloading={this.state.mmxloading}
                                                         total_price={this.props.total}
                                                         fee={this.props.fee_charge}
                                                         currency_symbol={this.props.currency_symbol} config={JSON.parse(this.props.config)}/>}
                />
                {this.state.show_payment_section ?
                    <div>
                        <EmOptionSelector option_select_callback={this.selectTypePayment}/>
                        {this.state.typePayment === 1 ?
                            <EmCard payment_object={this.props.payment_object} />
                            : ""
                        }
                    </div>
                    : ""
                }
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
        return accounting.formatMoney( CurrencyFormat.value, CurrencyFormat.currency_symbol + "", 2 );
    },
    getCurrencyFormattedFromValue : function(value){
        return accounting.formatMoney( value, CurrencyFormat.currency_symbol + "", 2 );
    }
};

var PowerPlayCart = {
    powerPlayPrice : 0,
    numLines : 0,
    PowerPlay : 0,
    total : 0,
};

var LogicCart = {
    total : 0,
    pre_total : 0,
    fee_limit : 0,
    isPayWithWallet : false,
    fundsValue : 0,
    show_all_fee : true,
    show_fee_value : true,
    show_fee_text : true,

    payWithWallet : function () {
        var current_total = LogicCart.total;
        var pre_total = LogicCart.pre_total;
        var totalWithWallet = Wallet.getTotalWhenPayed(LogicCart.total);
        if( accounting.unformat(LogicCart.fee_limit) > accounting.unformat(totalWithWallet) ) {
            LogicCart.total = Funds.getTotalWhenFundsAreInserted(totalWithWallet);
            pre_total = Funds.getTotalWhenFundsAreInserted(LogicCart.pre_total);
            if( accounting.unformat(LogicCart.total) < accounting.unformat(LogicCart.fee_limit) &&
                accounting.unformat(totalWithWallet) > 0 ) {
                LogicCart.pre_total = Fee.checkFeeWithWallet(pre_total);
                LogicCart.total = Fee.checkFeeWithWallet(LogicCart.total);
                if(Fee.applied) {
                    LogicCart.show_fee_text = true;
                    LogicCart.show_fee_value = true;
                    LogicCart.show_all_fee = true;
                } else {
                    LogicCart.show_fee_text = true;
                    LogicCart.show_fee_value = false;
                    LogicCart.show_all_fee = true;
                }
            } else {
                if( Funds.funds_value > 0 ) {
                    LogicCart.show_all_fee = true;
                    LogicCart.show_fee_value = false;
                    LogicCart.show_fee_text = false;
                } else {
                    LogicCart.pre_total = Funds.getTotalWhenFundsAreInserted(LogicCart.pre_total);
                    if(accounting.unformat(LogicCart.pre_total) > accounting.unformat(Wallet.wallet)) {
                        LogicCart.show_all_fee = true;
                        LogicCart.show_fee_value = true;
                        LogicCart.show_fee_text = true;
                    } else {
                        LogicCart.show_all_fee = false;
                        LogicCart.show_fee_value = false;
                        LogicCart.show_fee_text = false;
                    }
                }
            }
        } else {
            LogicCart.show_all_fee = false;
            LogicCart.show_fee_value = false;
            LogicCart.show_fee_text = false;
            LogicCart.total = Wallet.getTotalWhenPayed(LogicCart.total);
        }
    },

    payWithNoWallet : function () {
        var pre_total = LogicCart.pre_total;
        if( accounting.unformat(LogicCart.fee_limit)  > accounting.unformat(LogicCart.total)) {
            LogicCart.pre_total = Funds.getTotalWhenFundsAreInserted(pre_total);
            LogicCart.total = Funds.getTotalWhenFundsAreInserted(LogicCart.total);
            if( accounting.unformat(LogicCart.pre_total) < accounting.unformat(LogicCart.fee_limit) ) {
                LogicCart.pre_total = Fee.getTotalWithFee(LogicCart.pre_total);
                LogicCart.total = Fee.getTotalWithFee(LogicCart.total);
                LogicCart.show_all_fee = true;
                LogicCart.show_fee_value = true;
                LogicCart.show_fee_text = true;
            } else {
                LogicCart.show_all_fee = true;
                LogicCart.show_fee_value = false;
                LogicCart.show_fee_text = false;
            }
        } else {
            LogicCart.show_all_fee = false;
            LogicCart.show_fee_value = false;
            LogicCart.show_fee_text = false;
        }
    }
};

var Wallet = {
    total_balance : 0,
    isChecked : false,
    wallet : 0,

    getTotalWhenPayed : function (total) {
        Wallet.wallet = accounting.unformat(Wallet.total_balance) > accounting.unformat(total) ? total : Wallet.total_balance;
        if( accounting.unformat(Wallet.wallet) >= accounting.unformat(total) ) {
            return (accounting.unformat(Wallet.wallet) - accounting.unformat(total));
        } else {
            return ((accounting.unformat(total) - accounting.unformat(Wallet.wallet)));
        }
    },

    getNewWalletBalance : function () {
        return (parseFloat(Wallet.total_balance) - parseFloat(Wallet.wallet)).toFixed(2);
    }

};



var Funds = {
    funds_value : 0,
    getTotalWhenFundsAreInserted : function(value) {
        if ( accounting.unformat(Funds.funds_value) > 0 ) {
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
        if( accounting.unformat(value) >= accounting.unformat(LogicCart.fee_limit) ) {
            Fee.applied = false;
            return parseFloat(value).toFixed(2);
        } else {
            Fee.applied = true;
            return Fee.getTotalWithFee(value);
        }
    },

    getTotalWithFee : function(value) {
        return ((accounting.formatMoney(( accounting.unformat(Fee.fee_value)  + accounting.unformat(value)),CurrencyFormat.currency_symbol + " ",2)));
    }
};

ReactDOM.render(<CartPage total={total_price}
                          config={config}
                          checked_wallet={checked_wallet}
                          symbol_position={symbol_position}
                          draw_days={draw_days}
                          price_below_fee={price_below_fee}
                          fee_charge={fee_charge}
                          currency_symbol={currency_symbol}
                          play_list={play_list}
                          wallet_balance={wallet_balance}
                          single_bet_price={single_bet_price}
                          show_fee_line={show_fee_line}
                          discount={discount} txt_summary={txt_summary} txt_draws={txt_draws} txt_on={txt_on}
                          txt_currencyAlert={txt_currencyAlert} txt_total={txt_total}
                          txt_payWithBalanceOption={txt_payWithBalanceOption} txt_gotopay_btn={txt_gotopay_btn}
                          txt_buy_btn={txt_buy_btn} txt_checkout_fee={txt_checkout_fee} txt_edit={txt_edit}
                          txt_link_play={txt_link_play}
                          txt_link_powerball={txt_link_powerball}
                          txt_link_megamillions={txt_link_megamillions}
                          txt_link_eurojackpot={txt_link_eurojackpot}
                          txt_line={txt_line}
                          tuesday={tuesday}
                          friday={friday}
                          wednesday={wednesday}
                          saturday={saturday}
                          powerplay={powerplay}
                          powerplayprice={powerplayprice}
                          powerball={powerball}
                          megamillions={megamillions}
                          eurojackpot={_eurojackpot}
                          megasena={_megasena}
                          txt_lottery={txt_lottery}
                          txt_lottery_title={txt_lottery_title}
                          txt_number_of_draws={txt_number_of_draws}
                          txt_starting_date={txt_starting_date}
                          txt_ending_date={txt_ending_date}
                          playingPP={playingPP}
                          playingMM={playingMM}
                          txt_for={txt_for}
                          txt_since={txt_since}
                          txt_weeks={txt_weeks}
                          txt_amount={txt_amount}
                          txtMultTotalPrice={txtMultTotalPrice}
                          payment_object={payment_object}
/>, document.getElementById('cart-order'));




