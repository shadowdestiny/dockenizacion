var React = require('react');
var ReactDOM = require('react-dom');
var EuroMillionsLine = require('../components/EmLine.js');
var ThresholdPlay = require('../components/EmThresholdPlay.jsx');
var EuroMillionsBoxAction = require('../components/EmBoxActionPlay.jsx');
var EuroMillionsMultipleEmLines = require('../components/EmMultipleEmLines.jsx');
var EuroMillionsBoxBottomAction = require('../components/EmBoxBottomAction.jsx');
var EmDrawConfig = require('../components/EmDrawConfig.jsx');

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
            playDays : 1,
            duration : 1,
            numBets : 0,
            lines : [],
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
                    if(i > lines.length) {
                        for(let j=0; j< default_lines;j++) {
                           lines.push(0);
                        }
                    }
                    lines[i] = 1;
                } else {
                    storage[i].numbers = [];
                    storage[i].stars = [];
                    localStorage.setItem('bet_line', JSON.stringify(storage));
                }
            }
        }
        this.setState( { lines_default : default_lines, lines: lines, count_lines : lines.length -1 });
        this.updatePrice();
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

    handlerAddLines : function()
    {
        var current_lines = this.state.lines;
        var default_lines = this.state.lines_default;
        var allLinesFilled = true;
        var firstClick = (current_lines.length == default_lines );

        current_lines.forEach(function(value){
           if(value == 0) {
               allLinesFilled = false;
           }
        });

       // alert(default_lines);
        if(allLinesFilled || firstClick) {
            for(let i=0; i< default_lines;i++) {
                current_lines.push(0);
            }
           // $('.add-more').addClass('stop');
        } else {
          //  $('.add-more').addClass('stop');
        }
        this.setState( {count_lines : current_lines.length -1 , lines : current_lines} );
    },

    mouseOverBtnAddLines : function ()
    {
        var allLinesFilled = true;
        this.state.lines.forEach(function(value){
            if(value == 0) {
                allLinesFilled = false;
            }
        });
        if(allLinesFilled) {
            $('.add-more').removeClass('stop');
            $('.box-more').unbind('mouseenter mouseleave vclick');
        } else {
            $('.add-more').addClass('stop');
        }
        if($('.add-more').hasClass('stop')) {
            if(!$('.tipr').length) {
                $('.box-more').tipr({'mode':'top'});
            }
        } else {

        }
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
        this.state.playDays = value.split(',').length;
        this.updatePrice();
    },

    handleChangeDuration : function (value)
    {
        this.state.duration = value;
        this.updatePrice();
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
        this.setState( { price : total, clear_all : false, random_all : false } );
    },

    render : function ()
    {
        var elem = [];
        var numberEuroMillionsLine = this.state.lines_default;
        if(this.state.count_lines > 0) {
            numberEuroMillionsLine = this.state.count_lines ;
        }
        var random_all = this.state.random_all;
        var default_value = '75';
        var default_text = '75 millions €';
        var custom_value = 'custom';

        var options = [
            {text: '50 millions €', value: '50'},
            {text: default_text, value: default_value},
            {text: '100 millions €', value: '100'},
            {text: 'Choose threshold', value: custom_value}
        ];
        var options_draw_days = [
            {text: 'Tuesday & Friday' , value : '2,5'},
            {text: 'Tuesday', value : '2'},
            {text: 'Firday' , value : '5'}
        ];

        elem.push(<EuroMillionsMultipleEmLines add_storage={this.addLinesInStorage} clear_all={this.state.clear_all} callback={this.handleOfBetsLine} random_all={random_all} numberEuroMillionsLine={numberEuroMillionsLine} key="1"/>);
        elem.push(<EuroMillionsBoxAction mouse_over_btn={this.mouseOverBtnAddLines}  add_lines={this.handlerAddLines} lines={this.state.lines} random_all_btn={this.handlerRandomAll} clear_all_btn={this.handlerClearAll} key="2"/>)

        return (
            <div>
                {elem}
                <div className="box-bottom">
                    <div className="wrap">
                        <EuroMillionsBoxBottomAction price={this.state.price}/>
                        <div className="advanced-play">
                            <hr className="hr yellow" />
                            <a href="javascript:void(0);" className="close"><svg className="ico v-cancel-circle"
                                                                                 dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-cancel-circle"></use>'}}/>
                            </a>
                            <div className="cols">
                                <EmDrawConfig change_draw={this.handleChangeDraw} change_duration={this.handleChangeDuration}  options={options_draw_days} customValue={custom_value}/>
                                <ThresholdPlay  options={options} customValue={custom_value} defaultValue={default_value} defaultText={default_text}/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
});

module.exports = PlayPage;
ReactDOM.render(<PlayPage lines_default={5} />, document.getElementById('gameplay'));




