var React = require('react');
var ReactDOM = require('react-dom');
var EuroMillionsLine = require('../components/EmLine.js');
var ThresholdPlay = require('../components/EmThresholdPlay.jsx');
var EuroMillionsBoxAction = require('../components/EmBoxActionPlay.jsx');
var EuroMillionsMultipleEmLines = require('../components/EmMultipleEmLines.jsx');
var EuroMillionsBoxBottomAction = require('../components/EmBoxBottomAction.jsx');
var EmDrawConfig = require('../components/EmDrawConfig.jsx');

var PlayPage = React.createClass({

    getInitialState : function () {
        return {
            lines_default : 5,
            count_lines : 0,
            random_all : false,
            price : 0.00,
            numWeek : 1,
            playDays : 1,
            duration : 1,
            numBets : 0,
            lines : [],
            clear_all : false
        }
    },

    shouldComponentUpdate : function (nextProps, nextState) {
        if( nextState.lines != this.state.lines) return true;
        if( nextState.clear_all != this.state.clear_all) return true;
        if( nextState.random_all != this.state.random_all) return true;
        if( nextState.count_lines != this.state.count_lines ) return true;
        return nextState.price != this.state.price;
    },


    componentDidMount : function () {
        $(document).on('update_price', function(e,numBets,numWeek,playDays,duration) {
            (numWeek) ? this.state.numWeek = numWeek : this.state.numWeek;
            (playDays) ? this.state.playDays = playDays.split(',').length : this.state.playDays;
            (duration) ? this.state.duration = duration : this.state.duration;
            (numBets) ? this.state.numBets = numBets : this.state.numBets;
            this.updatePrice();
        }.bind(this));

        this.state.count_lines = this.state.lines_default + this.state.count_lines;
        this.updatePrice();
        this.setState(this.state);
    },


    handlerAddLines : function() {
        this.setState( { count_lines : this.state.lines_default + this.state.count_lines +1 });
    },
    handlerRandomAll : function() {
        this.setState( { random_all : true } );
    },
    handlerClearAll : function () {
        this.setState( { clear_all : true });
    },

    handleChangeDraw : function (value) {
        this.state.playDays = value.split(',').length;
        this.updatePrice();
    },

    handleChangeDuration : function (value) {
        this.state.duration = value;
        this.updatePrice();
    },


    getValidNumberBets : function(line, numbers,stars) {
        if(numbers == 5 && stars == 2) {
            this.state.lines[line] = 1;
        } else {
            this.state.lines[line] = 0;
        }
        this.state.numBets = this.state.lines;
        this.updatePrice();
    },

    updatePrice : function () {
        var numWeeks = this.state.duration;
        var playDays = this.state.playDays;
        var numDraws = numWeeks * playDays;
        var price = 2.35;
        var betsActive = 0;

        if(this.state.numBets.length > 0) {
            this.state.numBets.forEach(function(value) {
                if (value > 0) {
                    betsActive = betsActive + 1;
                }
            });
        }
        var total = Number(betsActive * price * numDraws).toFixed(2);
        this.setState( { price : total, clear_all : false, random_all : false } );
    },


    render : function () {

        var elem = [];
        var numberEuroMillionsLine = this.state.lines_default;
        if(this.state.count_lines > 0) {
            numberEuroMillionsLine = this.state.count_lines;
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
        if(varSize >= 4) { //varSize var is in main.js
            numberEuroMillionsLine = 1;
        }
        window.onresize = function() {
            if(varSize < 4) {
                if( numberEuroMillionsLine < 5 ) {
                    numberEuroMillionsLine = numberEuroMillionsLine + 1;
                    elem.push(<EuroMillionsLine random_all={random_all} numberPerLine="5" key="1" lineNumber={numberEuroMillionsLine}/>);
                }
            }
        }
        elem.push(<EuroMillionsMultipleEmLines clear_all={this.state.clear_all} callback={this.getValidNumberBets} random_all={random_all} numberEuroMillionsLine={numberEuroMillionsLine} key="1"/>);
        elem.push(<EuroMillionsBoxAction add_lines={this.handlerAddLines} lines={this.state.lines} random_all_btn={this.handlerRandomAll} clear_all_btn={this.handlerClearAll} key="2"/>)

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




