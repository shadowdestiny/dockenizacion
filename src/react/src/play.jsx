var React = require('react');
var ReactDOM = require('react-dom');
var EuroMillionsLine = require('../components/EmLine.js');
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
            duration : 1,
            date_play : 0,
            numBets : 0,
            lines : [],
            show_block_config : false,
            clear_all : false,
            storage : JSON.parse(localStorage.getItem('bet_line')) || []
        }
    },

    componentDidMount : function ()
    {
        window.addEventListener('resize', this.handleResize);
    },

    shouldComponentUpdate : function (nextProps, nextState)
    {
        if( nextState.show_block_config != this.state.show_block_config) return true;
        if(nextState.date_play != this.state.date_play) return true;
        if( nextState.playDays != this.state.playDays) return true;
        if( nextState.duration != this.state.duration) return true;
        if( nextState.show_tooltip_lines != this.state.show_tooltip_lines) return true;
        if( nextState.lines != this.state.lines) return true;
        if( nextState.clear_all != this.state.clear_all) return true;
        if( nextState.random_all != this.state.random_all) return true;
        if( nextState.count_lines != this.state.count_lines ) return true;
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
        localStorage.setItem('bet_line', JSON.stringify(this.state.storage, function(k,v){
                return (v == null) ? { 'numbers' : [], 'stars' : []} : v;
            }
        ));
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

    handlerAddLines : function(event)
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
        this.setState( { random_all : true } );
    },
    handlerClearAll : function ()
    {
        this.setState( { clear_all : true });
    },

    handleChangeDraw : function (value)
    {
        switch(value) {
            case 'Friday':
                value = 1;
                break;
            case 'Tuesday':
                value = 1;
                break;
            default:
                value = 2;
        }

        this.state.playDays = value;
        this.updatePrice();
    },

    handleChangeDuration : function (value)
    {
        this.state.duration = value;
        this.updatePrice();
    },

    handleChangeDate : function (value)
    {
        this.state.date_play = value;
        this.updatePrice();
    },

    handleClickAdvancedPlay : function (value)
    {
        var show_block_config = (!this.state.show_block_config);
        this.setState( {
            show_block_config : show_block_config
        });
    },

    handleOfBetsLine : function(line, numbers,stars)
    {
        if(numbers == 5 && stars == 2) {
            this.state.lines[line] = 1;
        } else {
            this.state.lines[line] = 0;
        }
        this.state.numBets = this.state.lines;
        this.updatePrice();
    },

    updatePrice : function ()
    {
        var numWeeks = this.state.duration;
        var playDays = this.state.playDays;
        var numDraws = numWeeks * playDays;

        var price = price_bet;
        var betsActive = 0;

        if(this.state.lines.length > 0) {
            this.state.lines.forEach(function(value) {
                if (value > 0) {
                    betsActive = betsActive + 1;
                }
            });
        }
        var total = Number(betsActive * price * numDraws).toFixed(2);
        this.setState( { date_play : this.state.date_play, price : total, clear_all : false, random_all : false } );
    },

    render : function ()
    {
        var elem = [];
        var numberEuroMillionsLine = this.state.lines_default;
        if(this.state.count_lines > 0) {
            numberEuroMillionsLine = this.state.count_lines ;
        }
        if(this.state.date_play == 0) {
            //EMTD pass as property
            this.state.date_play = '2015-12-22';
        }
        var random_all = this.state.random_all;

        elem.push(<EuroMillionsMultipleEmLines add_storage={this.addLinesInStorage} clear_all={this.state.clear_all} callback={this.handleOfBetsLine} random_all={random_all} numberEuroMillionsLine={numberEuroMillionsLine} key="1"/>);
        elem.push(<EuroMillionsBoxAction show_tooltip={this.state.show_tooltip_lines}  mouse_over_btn={this.mouseOverBtnAddLines}  add_lines={this.handlerAddLines} lines={this.state.lines} random_all_btn={this.handlerRandomAll} clear_all_btn={this.handlerClearAll} key="2"/>)

        return (
            <div>
                {elem}
                <div className="box-bottom">
                    <div className="wrap">
                        <EuroMillionsBoxBottomAction click_advanced_play={this.handleClickAdvancedPlay} date_play={this.state.date_play} duration={this.state.duration} play_days={this.state.playDays}  lines={this.state.storage}  price={this.state.price}/>
                        <EmConfigPlayBlock date_play={this.handleChangeDate} duration={this.handleChangeDuration} play_days={this.handleChangeDraw} show={this.state.show_block_config}/>
                    </div>
                </div>
            </div>
        )
    }
});

module.exports = PlayPage;
ReactDOM.render(<PlayPage lines_default={5} />, document.getElementById('gameplay'));




