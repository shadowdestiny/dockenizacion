var React = require('react');
var ReactDOM = require('react-dom');
var EuroMillionsBoxAction = require('../components/EmBoxActionPlay.jsx');
var EuroMillionsMultipleEmLines = require('../components/EmMultipleEmLines.jsx');
var EuroMillionsBoxBottomAction = require('../components/EmBoxBottomAction.jsx');
var EmConfigPlayBlock = require('../components/EmConfigPlayBlock.jsx');


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
            draw_day_play: 2,
            duration : 1,
            date_play : this.props.date_play,
            numBets : 0,
            lines : [],
            show_block_config : false,
            clear_all : false,
            show_clear_all : false,
            config_changed : false,
            show_config : true,
            reset_advanced_play : false,
            draw_dates : this.props.draw_dates,
            draw_duration : this.props.draw_duration,
            storage : JSON.parse(localStorage.getItem('bet_line')) || []
        }
    },

    componentDidMount : function ()
    {
        window.addEventListener('resize', this.handleResize);
        this.setState({ random_all : this.props.automatic_random });
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
        return nextState.price != this.state.price;
    },


    componentWillMount : function ()
    {
        if(varSize >= 4) {
            this.state.lines_default = 2;
        }
        var storage = this.state.storage;
        var lines = this.state.lines;
        var default_lines = this.state.lines_default;


        if( storage != null ) {
            if(lines.length == 0) {
                for(let i=0; i< default_lines;i++) {
                    lines.push(0);
                }
            }
            for(let i=0;i<storage.length;i++) {
                if(storage[i].numbers.length > 0 || storage[i].stars.length > 0) {
                    if(i > lines.length-1 ) {
                        for(let j=0; j< default_lines;j++) {
                            lines.push(0);
                        }
                    }
                    lines[i] = 1;
                } else {
                    storage[i].numbers = [];
                    storage[i].stars = [];
                    localStorage.setItem('bet_line', JSON.stringify(storage));
                    this.state.show_tooltip_lines = true;
                }
            }
        }
        var show_tooltip_lines = (this.checkBetsConfirmed() || lines.length <= default_lines) ? false : true;
        this.setState( { show_tooltip_lines : show_tooltip_lines, lines_default : default_lines, lines: lines, count_lines : lines.length -1 });
        this.updatePrice();
    },


    getNumLinesThatAreFilled : function ()
    {
        var current_lines = this.state.storage;
        var num_valid_lines = 0;

        current_lines.forEach(function(value) {
            if(value.numbers.length == 5 && value.stars.length == 2) {
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
        localStorage.setItem('bet_line', JSON.stringify(this.state.storage, function(k,v){
                return (v == null || v == 'null') ? { 'numbers' : [], 'stars' : []} : v;
            }));

        this.updatePrice();
    },

    handleResize : function ()
    {
        var varSize = checkSize();
        if(varSize >= 4) {
            this.state.lines_default = 2;
        }else {
            this.state.lines_default = 6;
        }
        var default_lines = this.state.lines_default -1;
        var count_lines = this.state.count_lines;
        if(varSize < 4 && (count_lines < default_lines)) {
            this.setState({ count_lines : count_lines +1 });
        }
    },

    handlerAddLines : function()
    {
        var show_tooltip = this.state.show_tooltip_lines;
        var current_lines = this.state.lines;
        var default_lines = this.state.lines_default;
        var firstClick = (current_lines.length == default_lines );
        var allLinesFilled = this.checkBetsConfirmed();
        if(allLinesFilled || firstClick) {
            for(let i=0; i< default_lines;i++) {
                current_lines.push(0);
            }
            show_tooltip = true;
        }
        this.setState( { show_tooltip_lines: show_tooltip,  count_lines : current_lines.length -1 , lines : current_lines} );
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
                         draw_dates : draw_dates,
                         draw_duration : options_draw_duration});

        this.updatePrice();
    },

    handleChangeDuration : function (value)
    {
        this.setState({
            duration : value,
            config_changed : true
        });
        this.updatePrice();
    },

    handleChangeDate : function (value)
    {
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
                reset_advanced_play : true ,
                draw_day_play : 2
            }
        )
    },

    handleResetStateAdvancedPlay : function () {
        this._resetStateAdvancedPlay();
    },

    setChangedWhenThresholdUpdate : function () {
        this.setState( { config_changed : true, show_config : true} );
    },


    handleTouchStart : function ()
    {

    },

    updatePrice : function ()
    {
        var numWeeks = this.state.duration;
        var playDays = this.state.playDays;
        var numDraws = numWeeks * playDays;
        var betsActive = this.getNumLinesThatAreFilled();
        var total = Number(betsActive * price_bet * numDraws).toFixed(2);
        var show_clear_all = this.checkNumbersOnLineStored() > 0;
        this.setState( { price : total, show_clear_all : show_clear_all, clear_all : false, random_all : false } );
    },

    render : function ()
    {
        var elem = [];
        var numberEuroMillionsLine = this.state.lines_default;
        if(this.state.count_lines > 0) {
            numberEuroMillionsLine = this.state.count_lines ;
        }
        var random_all = this.state.random_all;

        elem.push(<EuroMillionsMultipleEmLines add_storage={this.addLinesInStorage} clear_all={this.state.clear_all} callback={this.handleOfBetsLine} random_all={random_all} numberEuroMillionsLine={numberEuroMillionsLine} key="1"/>);
        elem.push(<EuroMillionsBoxAction next_draw_format={this.props.next_draw_format} show_tooltip={this.state.show_tooltip_lines}  mouse_over_btn={this.mouseOverBtnAddLines}  add_lines={this.handlerAddLines} lines={this.state.lines} random_all_btn={this.handlerRandomAll} show_clear_all={this.state.show_clear_all} clear_all_btn={this.handlerClearAll} key="2"/>);

        return (
            <div onTouchStart={this.handleTouchStart}>
                {elem}
                <div className="box-bottom">
                    <div className="wrap">
                        <EuroMillionsBoxBottomAction reset={this.handleResetStateAdvancedPlay} config_changed={this.state.config_changed} draw_day_play={this.state.draw_day_play} currency_symbol={this.props.currency_symbol} click_advanced_play={this.handleClickAdvancedPlay} date_play={this.state.date_play} duration={this.state.duration} play_days={this.state.playDays}  lines={this.state.storage}  price={this.state.price}/>
                        <EmConfigPlayBlock reset={this.handleResetStateAdvancedPlay} update_threshold={this.setChangedWhenThresholdUpdate}  show_config={this.state.show_config} reset_config={this.state.reset_advanced_play} draw_dates={this.state.draw_dates} date_play={this.handleChangeDate} current_duration_value={this.state.duration} draw_days_selected={this.state.draw_day_play} draw_duration={this.state.draw_duration} duration={this.handleChangeDuration} play_days={this.handleChangeDraw} show={this.state.show_block_config}/>
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

ReactDOM.render(<PlayPage next_draw_format={next_draw_format} currency_symbol={currency_symbol} automatic_random={automatic_random}  lines_default={5} date_play={""+draw_dates[0]} draw_duration={options_draw_duration} draw_dates={draw_dates}/>, document.getElementById('gameplay'));




