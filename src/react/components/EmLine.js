var React = require('react');
var EuroMillionsLineRow = require('./EmLineRow.js');
var EuroMillionsLineStarsRow = require('./EmLineStarsRow.js');
var EuroMillionsClearLine = require('./EmClearLine.js');
var EuroMillionsCheckMark = require('./EmIcoCheckMark.js');
var EuroMillionsRandomBtn = require('./EmRandomBtn.js');

var EuroMillionsLine = React.createClass({

    getDefaultProps: function() {
        return {
            maxNumbers : 5,
            maxStars : 2
        }
    },
    getInitialState: function () {
        var showClearBtn = false;
        var numbers = [];
        var stars = [];
        if(this.props.storage != null || typeof this.props.storage != 'undefined') {
                numbers = this.props.storage.numbers;
                stars = this.props.storage.stars;
        }

        return {
            isAnimated : false,
            show_btn_clear : showClearBtn,
            selectedNumbers: {
                'numbers': numbers,
                'stars': stars
            }
        };
    },

    componentWillReceiveProps: function(nextProps) {
        if(nextProps.random) {
            this.randomAll();
            this.storePlay();
        }
        if(nextProps.clear_all){
            this.state.selectedNumbers.numbers = [];
            this.state.selectedNumbers.stars = [];
            this.storePlay();
        }
    },

    componentDidMount: function() {
        if(this.isMounted()) {
            $(document).on('clear_line',function(e) {
                this.state.selectedNumbers.numbers = [];
                this.state.selectedNumbers.stars = [];
                this.storePlay();
                this.checkNumbersForActions();
                this.setState({selectedNumbers : {
                                numbers : [],
                                stars : []
                              }});
            }.bind(this));
        }
    },

    componentWillUpdate : function (nextProps, nextState) {
        if(this.props.random) {
            this.randomAll();
            this.storePlay();
        }
        var numbers_length = this.state.selectedNumbers.numbers.length;
        var stars_length = this.state.selectedNumbers.stars.length;
        this.props.callback( this.props.lineNumber,numbers_length,stars_length);
    },


    handleClickOnNumber: function (number) {
        if (typeof number != 'undefined') {
            var position = this.state.selectedNumbers.numbers.indexOf(number);
            if (position == -1) {
                if( this.state.selectedNumbers.numbers.length < this.props.maxNumbers ) {
                    this.state.show_btn_clear = true;
                    this.state.selectedNumbers.numbers.push(number);
                }
            } else {
                this.state.show_btn_clear = true;
                this.state.selectedNumbers.numbers.splice(position, 1);
            }
            this.storePlay();
            this.checkNumbersForActions();
            this.setState( {numbers : this.state.selectedNumbers.numbers });
        }
    },


    storePlay : function() {
        this.props.addLineInStorage(null,this.props.lineNumber,this.state.selectedNumbers.numbers, this.state.selectedNumbers.stars);
    },

    checkNumbersForActions : function () {
        var linenumber = this.props.lineNumber
        var numbers_length = this.state.selectedNumbers.numbers.length;
        var stars_length = this.state.selectedNumbers.stars.length;

        if(numbers_length == 0 && stars_length == 0) {
            $(document).trigger('show_btn_clear',[ linenumber, false ]);
        } else {
            $(document).trigger('show_btn_clear',[ linenumber, true ]);
        }


    },

    handleClickOnStar: function (star) {
        if (typeof star != 'undefined') {
            var position = this.state.selectedNumbers.stars.indexOf(star);
            if (position == -1) {
                if( this.state.selectedNumbers.stars.length < this.props.maxStars ) {
                    this.state.show_btn_clear = true;
                    this.state.selectedNumbers.stars.push(star);
                }
            } else {
                this.state.show_btn_clear = true;
                this.state.selectedNumbers.stars.splice(position, 1);
            }
            this.storePlay();
            this.checkNumbersForActions();
            this.setState( {numbers : this.state.selectedNumbers.stars });
        }
    },

    handleClickOnClear: function() {
        this.state.show_btn_clear = false;
        this.props.addLineInStorage(null,this.props.lineNumber,[], []);
        this.checkNumbersForActions();
        this.setState( {selectedNumbers : {
                            numbers : [],
                            stars : []
                        },
                        show_btn_clear: false
        });
        this.props.callback( this.props.lineNumber,0,0);
    },

    handleClickRandom : function () {
        this.randomAll();
        this.storePlay();
        this.checkNumbersForActions();
        this.props.callback( this.props.lineNumber,this.state.selectedNumbers.numbers.length,this.state.selectedNumbers.stars.length);
        //this.setState(this.state);
    },
    randomAll : function() {
        var nums = [];
        var stars = [];
        for(var i=0; i < 5; i++){
            var n = Math.floor(Math.random() * 51);
            if(nums.indexOf(n) == -1) nums[i] = n; else i--;
        }
        for(var i=0; i < 2; i++){
            var s = Math.floor(Math.random() * 12);
            if(stars.indexOf(s) == -1) stars[i] = s; else i--;
        }
        this.state.selectedNumbers.numbers = nums;
        this.state.selectedNumbers.stars = stars;
        this.state.show_btn_clear = true;
    },

    render: function () {
        var rows = [];
        var linenumber = this.props.lineNumber + 1;
        var numbers_length = this.state.selectedNumbers.numbers.length;
        var stars_length = this.state.selectedNumbers.stars.length;
        if(numbers_length === 0 && stars_length === 0) {
            this.state.show_btn_clear = false;
        } else {
            this.state.show_btn_clear = true;
        }
        for (var i = 1; i <= 50; i = i + j) {
            var row = [];
            for (var j = 0; j < this.props.numberPerLine; j++) {
                row.push(i + j)
            }

            rows.push(<EuroMillionsLineRow numbers={row} onNumberClick={this.handleClickOnNumber}
                                           selectedNumbers={this.state.selectedNumbers} key={row[0]}/>);
        }
        var star_rows = [];
        var star_numbers = [];
        for (var k = 1; k <= 4; k++) {
            star_numbers.push(k);
        }
        star_rows.push(<EuroMillionsLineStarsRow numbers={star_numbers} onStarClick={this.handleClickOnStar} selectedNumbers={this.state.selectedNumbers}
                                                 extraClass="" columnClass="col3 not" key="1"/>);
        star_numbers = [];
        for (; k <= 7; k++) {
            star_numbers.push(k);
        }
        star_rows.push(<EuroMillionsLineStarsRow numbers={star_numbers} onStarClick={this.handleClickOnStar} selectedNumbers={this.state.selectedNumbers}
                                                 extraClass=" extra-pad" columnClass="col4 not" key="2"/>);
        star_numbers = [];
        for (; k <= 11; k++) {
            star_numbers.push(k);
        }
        star_rows.push(<EuroMillionsLineStarsRow numbers={star_numbers} onStarClick={this.handleClickOnStar} selectedNumbers={this.state.selectedNumbers}
                                                 extraClass="" columnClass="col3 not" key="3"/>);

        var class_name = "myCol num"+this.props.lineNumber;
        return (
            <div onLoad={this.count} className={class_name}>
                <h1 className="h3 blue center">Line {  linenumber }</h1>
                <div className="line center">
                    <EuroMillionsCheckMark numbers_length={numbers_length} stars_length={stars_length}/>
                    <div className="combo cols not">
                        <EuroMillionsRandomBtn line={this.props.lineNumber} onBtnRandomClick={this.handleClickRandom}/>
                    </div>
                    <div className="values">
                        <div className="numbers">
                            {rows}
                        </div>
                        <div className="stars">
                            {star_rows}
                        </div>
                    </div>
                    <EuroMillionsClearLine showed={this.state.show_btn_clear} onClearClick={this.handleClickOnClear}/>
                </div>
            </div>
        );
    }
});

module.exports = EuroMillionsLine;