var React = require('react');
var EuroMillionsLineRow = require('./EmLineRow.js');
var EuroMillionsLineStarsRow = require('./EmLineStarsRow.js');
var EuroMillionsClearLine = require('./EmClearLine.js');
var EuroMillionsCheckMark = require('./EmIcoCheckMark.js');
var EuroMillionsRandomBtn = require('./EmRandomBtn.js');

const GAME_MODE_POWERBALL = 'powerball'
const GAME_MODE_EUROMILLIONS = 'euromillions'

var EuroMillionsLine = React.createClass({

    getInitialState: function ()
    {
        var showClearBtn = false;
        var numbers = [];
        var stars = [];
        const {
          storage,
          maxNumbers,
          maxStars,
          gameMode,
        } = this.props

        if(storage != null || typeof storage != 'undefined') {
          numbers = storage.numbers;
          stars   = storage.stars;
        }
        return {
            isAnimated : false,
            show_btn_clear : showClearBtn,
            selectedNumbers: {
                'numbers': numbers,
                'stars': stars
            },
            maxStars      : maxStars || gameMode == GAME_MODE_POWERBALL ? 1 : 2,
            maxNumbers    : 5,
            highestNumber : gameMode == GAME_MODE_POWERBALL ? 69 : 50,
            highestStar   : gameMode == GAME_MODE_POWERBALL ? 26 : 12,
        };
    },

    componentDidUpdate : function (prevProps, prevState)
    {
        if( prevState.isAnimated ) {
            this.state.isAnimated = false;
            //this.setState({
            //    isAnimated : false
            //});
        }
    },

    componentWillReceiveProps: function(nextProps)
    {
        if(this.state.isAnimated) this.state.isAnimated = false;

        if(nextProps.clear_all){
            this.state.selectedNumbers.numbers = [];
            this.state.selectedNumbers.stars = [];
            this.state.isAnimated = false;
            this.storePlay();
        }
    },

    componentWillUpdate : function (nextProps, nextState)
    {
        if(nextProps.random) {
            this.randomAll(false);
        }
        var numbers_length = this.state.selectedNumbers.numbers.length;
        var stars_length = this.state.selectedNumbers.stars.length;
        this.props.callback( this.props.lineNumber,numbers_length,stars_length);
    },


    handleClickOnNumber: function (number)
    {
        if (typeof number != 'undefined') {
            var position = this.state.selectedNumbers.numbers.indexOf(number);
            if (position == -1) {
                if( this.state.selectedNumbers.numbers.length < this.state.maxNumbers ) {
                    this.state.show_btn_clear = true;
                    this.state.selectedNumbers.numbers.push(number);
                }
            } else {
                this.state.show_btn_clear = true;
                this.state.selectedNumbers.numbers.splice(position, 1);
            }
            this.storePlay();
            this.setState( {numbers : this.state.selectedNumbers.numbers } );
        }
    },


    storePlay : function(numbers = null, stars = null)
    {
      numbers = numbers === null ? this.state.selectedNumbers.numbers : numbers
      stars   = stars === null ? this.state.selectedNumbers.stars : stars
      this.props.addLineInStorage(null,this.props.lineNumber, numbers, stars);
    },

    handleClickOnStar: function (star)
    {
        if (typeof star != 'undefined') {
            var position = this.state.selectedNumbers.stars.indexOf(star);
            if (position == -1) {
                if( this.state.selectedNumbers.stars.length < this.state.maxStars ) {
                    this.state.show_btn_clear = true;
                    this.state.selectedNumbers.stars.push(star);
                }
            } else {
                this.state.show_btn_clear = true;
                this.state.selectedNumbers.stars.splice(position, 1);
            }
            this.storePlay();
            this.setState( {numbers : this.state.selectedNumbers.stars });
        }
    },

    handleClickOnClear: function()
    {
        this.state.show_btn_clear = false;
        this.props.addLineInStorage(null,this.props.lineNumber,[], []);
        this.state.isAnimated = false;
        this.setState( {selectedNumbers : {
                            numbers : [],
                            stars : []
                        },
                        show_btn_clear: false,
        })
        this.props.callback(this.props.lineNumber,0,0);
    },

    handleClickRandom : function ()
    {
        this.randomAll(true);
        this.props.callback( this.props.lineNumber,this.state.selectedNumbers.numbers.length,this.state.selectedNumbers.stars.length);
    },

    randomAll : function(animated)
    {
        var nums = [];
        var stars = [];
        const {
          highestNumber,
          highestStar,
          maxNumbers,
          maxStars,
        } = this.state

        for(var i=0; i < maxNumbers; i++){
            var n = Math.floor(Math.random() * (highestNumber + 1));
            if(nums.indexOf(n) == -1) nums[i] = n; else i--;
            if(n == 0) i--;
        }
        for(var i=0; i < maxStars; i++){
            var s = Math.floor(Math.random() * (highestStar + 1));
            if(stars.indexOf(s) == -1) stars[i] = s; else i--;
            if(s == 0) i--;
        }
        this.state.selectedNumbers.numbers = nums;
        this.state.selectedNumbers.stars = stars;
        this.state.isAnimated = animated;
        this.storePlay();
        this.setState({
                isAnimated: animated,
                show_btn_clear : true

            }
        )
    },

    render: function ()
    {
        const { selectedNumbers } = this.state
        const { lineNumber, gameMode } = this.props

        const showStars = gameMode == GAME_MODE_EUROMILLIONS
        const showDropdown = gameMode == GAME_MODE_POWERBALL

        var alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var num_char_line = '';
        for(var c = 0; c < alphabet.length; c++) {
            num_char_line = alphabet.charAt(lineNumber)
            if( !num_char_line ) {
                var cur_pos = (lineNumber - alphabet.length);
                var new_pos = (lineNumber - alphabet.length) +2;
                num_char_line = alphabet.charAt(cur_pos) +""+ alphabet.charAt(new_pos);
            }
        }

        var numbers_length = selectedNumbers.numbers.length;
        var stars_length = selectedNumbers.stars.length;

        // direct state change??
        if(numbers_length === 0 && stars_length === 0) {
          this.state.show_btn_clear = false;
        } else {
          this.state.show_btn_clear = true;
        }
        var class_name = "myCol num"+this.props.lineNumber;
        return (
            <div onLoad={this.count} className={class_name}>
                <span className="h4 blue center">{this.props.txtLine} {  num_char_line }</span>
                <div className="line center">
                    <EuroMillionsCheckMark numbers_length={numbers_length} stars_length={stars_length}/>
                    <div className="combo cols not">
                        <EuroMillionsRandomBtn line={this.props.lineNumber} onBtnRandomClick={this.handleClickRandom}/>
                    </div>
                    <div className="values">
                        {this.renderNumbersBlock()}
                        {showStars ? this.renderStarsBlock() : null}
                        {showDropdown ? this.renderPowerBallDropdown() : null}
                    </div>
                    <EuroMillionsClearLine clear_btn={clear_btn} showed={this.state.show_btn_clear} onClearClick={this.handleClickOnClear}/>
                </div>
            </div>
        );
    },

    renderNumbersBlock : function () {
      const {
        isAnimated,
        selectedNumbers,
        highestNumber,
      } = this.state

      const { numberPerLine } = this.props

      const rows = []
      let numbers = []

      for (let i = 1; i <= highestNumber; i ++) {
        numbers.push(i)
        if ((i % numberPerLine == 0 && numbers.length) || i == highestNumber) {
          rows.push(<EuroMillionsLineRow
            key={i}
            random_animation={isAnimated}
            numbers={numbers}
            onNumberClick={this.handleClickOnNumber}
            selectedNumbers={selectedNumbers}
          />)
          numbers = []
        }
      }

      return (
        <div className="numbers">
            {rows}
        </div>
      )
    },

    renderStarsBlock : function () {
      const { starsPerLine } = this.props
      const {
        highestStar,
        isAnimated,
        selectedNumbers,
      } = this.state

      const rows = []
      let numbers = []

      for (let i = 1; i <= highestStar; i ++) {
        numbers.push(i)
        if ((i % starsPerLine == 0 && numbers.length) || i == highestStar) {
          rows.push(<EuroMillionsLineStarsRow
            key={i}
            random_animation={isAnimated}
            numbers={numbers}
            onStarClick={this.handleClickOnStar}
            selectedNumbers={selectedNumbers}
            extraClass=""
            columnClass="col3 not"
          />)
          numbers = []

        }
      }

      return (
        <div className="stars">
            {rows}
        </div>
      )
    },

    renderPowerBallDropdown : function () {
      const { highestStar } = this.state
      const { stars } = this.state.selectedNumbers
      const { translations } = this.props
      const options = [
        <option key={-1} value={-1}></option>
      ]
      for (let i = 1; i <= highestStar; i ++) {
        options.push(<option key={i} value={i}>{i}</option>)
      }

      return (
        <div className="powerballlabel">
          <span>{translations.powerballLabel}</span>
          <select
            value={stars && stars[0] ? stars[0] : -1}
            onChange={e => this.handlePowerballSelection(e.target.value)}
          >
            {options}
          </select>
        </div>
      )
    },

    handlePowerballSelection: function (value) {
      value = parseInt(value)
      if (value == -1) {
        return
      }

      this.storePlay(null, [value])
      const { selectedNumbers } = this.state

      this.setState({ selectedNumbers : {
        numbers : selectedNumbers.numbers,
        stars   : [value]
      }})
    },

});

module.exports = EuroMillionsLine;
