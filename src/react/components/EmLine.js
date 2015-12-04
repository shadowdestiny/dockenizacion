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
        var numbers = [];
        var stars = [];
        var storage = this.props.storage;
        var showClearBtn = false;
        var localStoredNumbers = JSON.parse(localStorage.getItem('bet_line'));
        if(localStoredNumbers != null && typeof localStoredNumbers[this.props.lineNumber] != 'undefined') {
            if(localStoredNumbers[this.props.lineNumber] != null) {
                numbers = localStoredNumbers[this.props.lineNumber].numbers;
                stars = localStoredNumbers[this.props.lineNumber].stars;
                showClearBtn = true;
            }
        }
        return {
            isAnimated : false,
            storage : storage,
            showedLine : showClearBtn,
            selectedNumbers: {
                'numbers': numbers,
                'stars': stars
            }
        };
    },

    componentDidMount: function() {
        this.state.storage[this.props.lineNumber] = this.state.selectedNumbers
    },

    handleClickOnNumber: function (number) {
        if (typeof number != 'undefined') {
            var position = this.state.selectedNumbers.numbers.indexOf(number);
            if (position == -1) {
                if( this.state.selectedNumbers.numbers.length < this.props.maxNumbers ) {
                    this.state.showedLine = true;
                    this.state.selectedNumbers.numbers.push(number);
                }
            } else {
                this.state.showedLine = true;
                this.state.selectedNumbers.numbers.splice(position, 1);
            }
            this.storePlay();
        }
    },

    storePlay : function() {
        this.state.storage[this.props.lineNumber] = this.state.selectedNumbers;
        localStorage.setItem('bet_line', JSON.stringify(this.state.storage));
        this.setState(this.state);
    },

    handleClickOnStar: function (star) {
        if (typeof star != 'undefined') {
            var position = this.state.selectedNumbers.stars.indexOf(star);
            if (position == -1) {
                if( this.state.selectedNumbers.stars.length < this.props.maxStars ) {
                    this.state.showedLine = true;
                    this.state.selectedNumbers.stars.push(star);
                }
            } else {
                this.state.showedLine = true;
                this.state.selectedNumbers.stars.splice(position, 1);
            }
            this.storePlay();
        }
    },
    handleClickOnClear: function() {
        this.state.selectedNumbers.numbers = [];
        this.state.selectedNumbers.stars = [];
        this.state.showedLine = false;
        this.storePlay();
    },

    handleClickRandom : function () {
        var nums = [];
        var stars = [];
        var repeat = 50;
        var x = 0;
        var self = this;
        var intervalID = setInterval(function(){
            for(var i=0; i < 5; i++){
                nums[i] = Math.floor(Math.random() * 50);
                self.state.selectedNumbers.numbers = nums[i];
            }
            for(var i=0; i < 2; i++){
                stars[i] = Math.floor(Math.random() * 11);
                self.state.selectedNumbers.stars = stars[i];
            }
            if(repeat === ++x) clearInterval(intervalID);
        },repeat);
        this.state.showedLine = true;
        this.storePlay();
    },
    render: function () {
        var rows = [];
        var linenumber = this.props.lineNumber + 1;
        var numbers_length = this.state.selectedNumbers.numbers.length;
        var stars_length = this.state.selectedNumbers.stars.length;

        if(numbers_length == 0 && stars_length == 0) {
            this.state.showedLine = false;
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

        return (
            <div>
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
                    <EuroMillionsClearLine showed={this.state.showedLine} onClearClick={this.handleClickOnClear}/>
                </div>
            </div>

        );
    }
});

module.exports = EuroMillionsLine;