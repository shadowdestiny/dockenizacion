var React = require('react');
var ReactDOM = require('react-dom');
var EuroMillionsBoxAction = require('../components/EmBoxActionPlay.jsx');
var EuroMillionsMultipleEmLines = require('../components/EmMultipleEmLines.jsx');
var EuroMillionsBoxBottomAction = require('../components/EmBoxBottomAction.jsx');
var EmConfigPlayBlock = require('../components/EmConfigPlayBlock.jsx');
var EmDiscountLines = require('../components/EmDiscountLines.jsx');

import { MobilePlayApp } from './mobile-play-app'

const MAX_MOBILE_WIDTH = 768

const TABLET_PORTRAIT_WIDTH = 992
const TABLET_LANDSCAPE_WIDTH = 1024

const GAME_MODE_POWERBALL = 'powerball'
const GAME_MODE_EUROMILLIONS = 'euromillions'

var PlayPage = React.createClass({

    getInitialState : function ()
    {
        return {
            lines_default : 6,
            count_lines : 0,
            random_all : false,
            price : 0.00,
            varSize : checkSize(),
            numWeek : 1,
            show_tooltip_lines : false,
            playDays : 1,
            draw_day_play: this.props.next_draw,
            duration : 1,
            date_play : this.props.date_play,
            numBets : 0,
            lines : [],
            show_block_config : false,
            clear_all : false,
            show_clear_all : false,
            config_changed : false,
            config_play_values : [ {draw_day : '', first_draw_date : '', duration : '' }],
            show_config : true,
            reset_advanced_play : false,
            draw_dates : this.props.draw_dates,
            draw_duration : this.props.draw_duration,
            storage : JSON.parse(localStorage.getItem(this.getStorageKey())) || [],
            draws_number: this.props.draws_number,
            discount: this.props.discount,
            mobileView : window.innerWidth < MAX_MOBILE_WIDTH,
            powerPlayEnabled : false,
        }
    },

    componentDidMount : function ()
    {
        window.addEventListener('resize', this.handleResize);
        this.state.config_play_values.draw_day = 1;
        this.state.config_play_values.first_draw_date = this.state.date_play;
        this.state.config_play_values.duration  = this.state.duration;
        this.setState({ random_all : this.props.automatic_random});
    },

    shouldComponentUpdate : function (nextProps, nextState)
    {

        if( nextState.draw_duration != this.state.draw_duration) return true;
        if( nextState.draw_dates != this.state.draw_dates) return true;
        if( nextState.show_block_config != this.state.show_block_config) return true;
        if(nextState.date_play != this.state.date_play) return true;
        if( nextState.playDays != this.state.playDays) return true;
        if( nextState.reset_advanced_play != this.state.reset_advanced_play ) return true;
        if( nextState.config_changed != this.state.config_changed ) return true;
        if( nextState.show_config != this.state.show_config ) return true;
        if( nextState.duration != this.state.duration) return true;
        if( nextState.show_tooltip_lines != this.state.show_tooltip_lines) return true;
        if( nextState.lines != this.state.lines) return true;
        if( nextState.clear_all != this.state.clear_all) return true;
        if( nextState.random_all != this.state.random_all) return true;
        if( nextState.count_lines != this.state.count_lines ) return true;
        if( nextState.show_clear_all != this.state.show_clear_all) return true;
        if( nextState.draws_number != this.state.draws_number) return true;
        if( nextState.discount != this.state.discount) return true;
        if( nextState.mobileView != this.state.mobileView) return true;
        if( nextState.powerPlayEnabled != this.state.powerPlayEnabled) return true;
        return nextState.price != this.state.price;
    },


    componentWillMount : function ()
    {
        this.handleResize();
        this.updatePrice();
    },

    getStorageKey : function () {
      const storageKeys = {
        [GAME_MODE_POWERBALL] : 'pb_bat_line',
        [GAME_MODE_EUROMILLIONS] : 'bet_line',
      }
      return storageKeys[this.props.mode]
    },

    getNumLinesThatAreFilled : function ()
    {
        var current_lines = this.state.storage;
        var num_valid_lines = 0;
        const { mode } = this.props
        const maxStars = mode == GAME_MODE_POWERBALL ? 1 : 2

        current_lines.forEach(function(value) {
            if(value.numbers.length == 5 && value.stars.length == maxStars) {
                num_valid_lines++;
            }
        });
        return num_valid_lines;
    },

    checkNumbersOnLineStored : function ()
    {
        var current_lines = this.state.storage;
        var num_valid_lines = 0;
        current_lines.forEach(function(value) {
            if(value.numbers.length > 0 || value.stars.length > 0) {
                num_valid_lines++;
            }
        });

        return num_valid_lines;
    },

    checkBetsConfirmed : function ()
    {
        var current_lines = this.state.lines;
        var allLinesFilled = true;
        current_lines.forEach(function(value){
            if(value == 0) {
                allLinesFilled = false;
            }
        });
        return allLinesFilled;
    },

    addLinesInStorage : function (e, line, numbers, stars)
    {
        this.state.storage[line] = {
            'numbers': numbers,
            'stars': stars
        };

        for (var i = this.state.storage.length - 1; i >= 0; i--) {
            if( this.state.storage[i] === 'undefined' || typeof this.state.storage[i] === 'undefined') {
                this.state.storage[i] = { 'numbers' : [], 'stars' : []};
            }
        }
        localStorage.setItem(this.getStorageKey(), JSON.stringify(this.state.storage, function(k,v){
                return (v == null || v == 'null') ? { 'numbers' : [], 'stars' : []} : v;
            }));

        this.updatePrice();
    },

    handleResize : function ()
    {
      const ticketsInRow  = this.getTicketsInRow()
      const ticketsToShow = this.getTicketsToShow()
      const lines = this.getLines(ticketsToShow)

      this.setState({
        lines_default : ticketsInRow,
        count_lines   : ticketsToShow,
        mobileView    : this.getWindowWidth() < MAX_MOBILE_WIDTH,
        lines,
      })
    },

    handlerAddLines : function()
    {
      let ticketsShown = this.state.lines.length
      const ticketsInRow = this.getTicketsInRow()
      const isFirstClick = ticketsShown / ticketsInRow == 1
      const allLinesFilled = this.checkBetsConfirmed()

      if (isFirstClick || allLinesFilled) {
        ticketsShown += ticketsInRow
        const lines = this.getLines(ticketsShown)
        this.setState({
          show_tooltip_lines : true,
          count_lines : ticketsShown,
          lines,
        })
      }
    },

    getWindowWidth : function () {
      return window.innerWidth
    },

    /**
     * getTicketsInRow - defines tickets number per row depending on screen width
     *
     * @return {Number}  tickets per row
     */
    getTicketsInRow : function () {
      const winWidth = this.getWindowWidth()
      let ticketsInRow
      if (winWidth <= TABLET_PORTRAIT_WIDTH) {
        ticketsInRow = 3
      } else if (winWidth <= TABLET_LANDSCAPE_WIDTH) {
        ticketsInRow = 4
      } else {
        ticketsInRow = 6
      }
      return ticketsInRow
    },

    /**
     * getTicketsToShow - defines tickets number to show depending on stored lines selected
     *                    and number of tickets per row (to populate rows to the full)
     *
     * @return {Number}  total quantity of ticket shown on the page
     */
    getTicketsToShow : function () {
      const { storage }  = this.state
      const ticketsInRow = this.getTicketsInRow()
      if (storage && storage.length) {
        let lastFilledLine = 0
        for (let i = storage.length - 1; i >= 0; i --) {
          if (storage[i].numbers.length > 0 || storage[i].stars.length > 0) {
            lastFilledLine = i
            break
          }
        }
        const tickersCount = lastFilledLine + 1
        return Math.ceil(tickersCount / ticketsInRow) * ticketsInRow
      }
      return ticketsInRow
    },

    /**
     * getLines - Returns array of flags (0 or 1) corresponding to the list
     *            of tickets shown. Where 0 means that no numbers/stars
     *            was chosen in the ticket and 1 if opposit
     *
     *            Have no idea why the property is ever needed
     *
     * @param  {Number} ticketsToShow tickets number to show
     * @return {Array}                list of 0 or 1 to store in state param `lines`
     */
    getLines : function (ticketsToShow) {
      const lines = []
      for (let i = 0; i < ticketsToShow; i ++) {
        const storedLine = this.state.storage[i]
        const isFilledLine = storedLine && (storedLine.numbers.length || storedLine.stars.length)
        lines.push(isFilledLine ? 1 : 0)
      }

      return lines
    },

    enablePowerPlay : function (enable) {
      this.setState({ powerPlayEnabled : !!enable })
    },

    mouseOverBtnAddLines : function ()
    {
        var current_lines = this.state.lines;
        var default_lines = this.state.lines_default;
        var allLinesFilled = this.checkBetsConfirmed();
        var firstClick = (current_lines.length == default_lines );
        var show_tooltip_lines = (allLinesFilled || firstClick) ? false : true;
        this.setState( { show_tooltip_lines : show_tooltip_lines } );
    },

    handlerRandomAll : function()
    {
        //var random  = this.state.lines_default >= this.state.count_lines;
        this.setState( { random_all : true } );
    },

    handlerClearAll : function ()
    {
        this.setState( { clear_all : true });
    },

    handleChangeDraw : function (value)
    {
        var length_value_day = 0;
        switch(parseInt(value)) {
            case 5:
                value = 5;
                length_value_day = 1;
                break;
            case 2:
                value = 2;
                length_value_day = 1;
                break;
            default:
                value = 25;
                length_value_day = 2;
        }
        var draw_dates = this.props.draw_dates;

        if(value < 25) {
            draw_dates = this.props.draw_dates.filter(function(value_dates) {
                var value_date = String(value_dates);
                return (value_date.substr(value_date.length -1) == value)
            });
       }
        var options_draw_duration = this.props.draw_duration;
        if(length_value_day > 1) {
            options_draw_duration = [
                {text : '1 week (Draws: 2)' , value : 1},
                {text : '2 weeks (Draws: 4)' , value : 2},
                {text : '4 weeks (Draws: 8)' , value : 4},
                {text : '8 weeks (Draws: 16)' , value : 8},
                {text : '52 weeks (Draws: 104)' , value : 52}
            ];
        }

        this.setState( { playDays : length_value_day,
                         draw_day_play : value,
                         config_changed : true,
                         date_play : draw_dates[0],
                         draw_dates : draw_dates,
                         draw_duration : options_draw_duration});

        this.state.config_play_values.draw_day = length_value_day;
        this.updatePrice();
    },

    handleChangeDuration : function (value)
    {
        this.state.config_play_values.duration = value;
        this.setState({
            duration : value,
            config_changed : true,
        });
        this.updatePrice();
    },

    handleChangeDate : function (value)
    {
        this.state.config_play_values.first_draw_date = value;
        this.setState( { date_play : value, config_changed : true } );
        //this.updatePrice();
    },

    handleClickAdvancedPlay : function (event)
    {
        var click_show = this.state.show_block_config;
        this.setState( {
            show_block_config : click_show ? false : true,
            config_changed : true
        });
    },

    handleOfBetsLine : function(line, numbers,stars)
    {
        if(numbers > 0 || stars > 0) {
            this.state.lines[line] = 1;
        } else {
            this.state.lines[line] = 0;
        }
        this.updatePrice();
    },

    _resetStateAdvancedPlay : function () {

        var _draw_dates =  this.props.draw_dates;
        var _draw_duration  = this.props.draw_duration;

        this.setState( {
                playDays : 1,
                date_play : _draw_dates[0],
                draw_dates : _draw_dates,
                draw_duration : _draw_duration,
                draw_days_selected : 2,
                current_duration_value : 1,
                duration : 1,
                config_changed : false,
                show_block_config : false,
                show_config : true,
                reset_advanced_play : true,
                draw_day_play : 2
            }
        )
    },

    handleResetStateAdvancedPlay : function () {
        this.state.config_play_values.draw_day = 1;
        this.state.config_play_values.first_draw_date = this.props.date_play;
        this.state.config_play_values.duration  = 1;
        this._resetStateAdvancedPlay();
    },

    setChangedWhenThresholdUpdate : function (value) {
        if(value) {
            this.state.duration = 1;
            this.state.playDays = 1;
            this.setState({
               duration : 1,
               reset_advanced_play : false,
               playDays : 1,
               show_config : false
            });

        } else {
            this.state.duration = this.state.config_play_values.duration;
            this.state.playDays = this.state.config_play_values.draw_day;
            this.state.date_play = this.state.config_play_values.first_draw_date;
            this.updatePrice();
            this.setState({
                show_config : true
            })
        }
        this.updatePrice();
       // this.state.changed_config = true;
    },

    updatePrice : function ()
    {
        var numWeeks = this.state.duration;
        var playDays = this.state.playDays;
        var numDraws = numWeeks * playDays;
        var betsActive = this.getNumLinesThatAreFilled();
        var total = Number(betsActive * price_bet * numDraws).toFixed(2);
        var show_clear_all = this.checkNumbersOnLineStored() > 0;
        this.setState( { price : total, show_clear_all : show_clear_all, clear_all : false, random_all : false} );
    },

    updateTotalByDiscount: function (draws_number, discount)
    {
        this.setState({ draws_number : draws_number, discount: discount });
    },

    getTotalPriceWithDiscount: function (draws)
    {
        var lines = JSON.parse(this.props.discount_lines);
        var singlePrice = 0;
        lines.forEach(function(line){
            if (line.draws == draws){
                singlePrice = (line.singleBetPriceWithDiscount / 100).toFixed(2);
            }
        });
        if (singlePrice == 0){
            return this.state.price * draws;
        } else {
            return singlePrice * this.getNumLinesThatAreFilled() * draws;
        }
    },

    render : function ()
    {
        const { mode } = this.props
        var elem = [];
        var numberEuroMillionsLine = this.state.lines_default;
        if(this.state.count_lines > 0) {
            numberEuroMillionsLine = this.state.count_lines ;
        }
        var random_all = this.state.random_all;
        var totalPriceDescription = this.props.txtMultTotalPrice + ' ';
        var descriptionBeforeButtonPrice = this.getNumLinesThatAreFilled() + ' ' + this.props.txtMultLines + ' x ' + this.state.draws_number + ' ' + this.props.txtMultDraws + ' ';
        var total_price = this.getTotalPriceWithDiscount(this.state.draws_number).toFixed(2);

        elem.push(<EuroMillionsMultipleEmLines
          add_storage={this.addLinesInStorage}
          clear_all={this.state.clear_all}
          callback={this.handleOfBetsLine}
          random_all={random_all}
          numberEuroMillionsLine={numberEuroMillionsLine}
          key="1"
          txtLine={this.props.txtLine}
          gameMode={mode}
          storage={this.state.storage}
          translations={__initialState.translations}
        />);

        elem.push(<EuroMillionsBoxAction
          addlines_message={this.props.addlines_message}
          clear_btn={clear_btn}
          clearAllLines={this.props.clearAllLines}
          randomizeAllLines={this.props.randomizeAllLines}
          addLinesBtn={this.props.addLinesBtn}
          next_draw_format={this.props.next_draw_format}
          show_tooltip={this.state.show_tooltip_lines}
          mouse_over_btn={this.mouseOverBtnAddLines}
          add_lines={this.handlerAddLines}
          lines={this.state.lines}
          random_all_btn={this.handlerRandomAll}
          show_clear_all={this.state.show_clear_all}
          clear_all_btn={this.handlerClearAll}
          translations={__initialState.translations}
          showPowerPlayCheck={mode == GAME_MODE_POWERBALL}
          enablePowerPlay={this.enablePowerPlay}
          key="2"
        />);

        if (this.state.mobileView) {
          return <MobilePlayApp {...__initialState} />
        }

        return (
            <div className={"gameplay--section " + (mode == GAME_MODE_POWERBALL ? 'powerball-game' : 'euromillions-game')}>
                {elem}
                <div className="box-bottom">
                    <div className="wrap">
                        <EmDiscountLines next_draw={this.props.next_draw} rowSelected={this.state.draws_number} sendLineSelected={this.updateTotalByDiscount} title={this.props.discount_lines_title} discount_lines={this.props.discount_lines} currency_symbol={this.props.currency_symbol} />
                        <EuroMillionsBoxBottomAction
                          total_price_description={totalPriceDescription}
                          description_before_price={descriptionBeforeButtonPrice}
                          reset={this.handleResetStateAdvancedPlay}
                          config_changed={this.state.config_changed}
                          draw_day_play={this.state.draw_day_play}
                          currency_symbol={this.props.currency_symbol}
                          click_advanced_play={this.handleClickAdvancedPlay}
                          date_play={this.state.date_play}
                          duration={this.state.draws_number}
                          play_days={this.state.playDays}
                          lines={this.state.storage}
                          price={total_price}
                          txtNextButton={this.props.txtNextButton}
                          showBuyDrawDate={this.state.draws_number}
                          buyForDraw={this.props.buyForDraw}
                          handleChangeDate={this.handleChangeDate}
                          draw_dates={this.state.draw_dates}
                          next_draw={this.props.next_draw}
                          next_draw_date_format={next_draw_date_format}
                          tuesday={tuesday}
                          friday={friday}
                          powerPlayEnabled={this.state.powerPlayEnabled}
                          mode={mode}
                        />
                        <EmConfigPlayBlock next_draw={this.props.next_draw} buyForDraw={this.props.buyForDraw} reset={this.handleResetStateAdvancedPlay} update_threshold={this.setChangedWhenThresholdUpdate}  show_config={this.state.show_config} date_play={this.handleChangeDate} reset_config={this.state.reset_advanced_play} draw_dates={this.state.draw_dates}  current_duration_value={this.state.duration} draw_days_selected={this.state.draw_day_play} draw_duration={this.state.draw_duration} duration={this.handleChangeDuration} play_days={this.handleChangeDraw} show={this.state.show_block_config}/>
                    </div>
                </div>
            </div>
        )
    }
});

module.exports = PlayPage;

var options_draw_duration = [
    {text : '1 week (Draw: 1)' , value : 1},
    {text : '2 weeks (Draws: 2)' , value : 2},
    {text : '4 weeks (Draws: 4)' , value : 4},
    {text : '8 weeks (Draws: 8)' , value : 8},
    {text : '52 weeks (Draws: 52)' , value : 52}
];

ReactDOM.render(<PlayPage
  tuesday={tuesday}
  friday={friday}
  next_draw_date_format={next_draw_date_format}
  discount={discount}
  draws_number={draws_number}
  buyForDraw={buyForDraw}
  clear_btn={clear_btn}
  clearAllLines={clearAllLines}
  randomizeAllLines={randomizeAllLines}
  addLinesBtn={addLinesBtn}
  discount_lines_title={discount_lines_title}
  discount_lines={discount_lines}
  next_draw={next_draw}
  next_draw_format={next_draw_format}
  currency_symbol={currency_symbol}
  automatic_random={automatic_random}
  lines_default={5}
  date_play={""+draw_dates[0]}
  draw_duration={options_draw_duration}
  draw_dates={draw_dates}
  txtLine={txtLine}
  txtMultTotalPrice={txtMultTotalPrice}
  txtMultLines={txtMultLines}
  addlines_message={addlines_message}
  txtMultDraws={txtMultDraws}
  txtNextButton={txtNextButton}
  mode={__initialState.mode}
/>, document.getElementById('gameplay'));
