var React = require('react');

var EuroMillionsAdvancedPlay = require('./EmAdvancedPlay.jsx');
var EuroMillionsAddToCart = require('./EmAddToCart.jsx');

var EuroMillionsBoxBottomAction = React.createClass({

    getInitialState: function () {
        return {
            price : 0.00,
            lines : []
        }
    },

    componentDidMount: function(){
        $(document).on('lines_to_add',function(e,line) {
            this.state.lines[line.linenumber] = 1;
            this.updatePrice();
        }.bind(this));

        $(document).on('lines_to_remove',function(e,line) {
            if(this.state.lines[line.linenumber] > 0 ) {
                this.state.lines[line.linenumber] = 0;
            }
            this.updatePrice();
        }.bind(this));
    },

    addToCart : function () {

    },

    updatePrice : function () {
        var numWeeks = $('.frequency').val();
        var playDays = $('.draw_days').val().split(',').length;
        var numDraws = numWeeks * playDays;
        var price = 2.35;
        var betsActive = 0;
        this.state.lines.forEach(function(value) {
            if (value > 0) {
                betsActive = betsActive + 1;
            }
        });
        this.state.price = Number(betsActive * price * numDraws).toFixed(2);
        this.setState( this.state );
    },

    render : function () {
        var elem = [];
        var price = this.state.price;
        elem.push(<EuroMillionsAdvancedPlay key="1"/>);
        elem.push(<EuroMillionsAddToCart onBtnAddToCartClick={this.addToCart} price={price} key="2"/>);

        return (
            <div className="right">
                {elem}
            </div>
        )
    }
})


module.exports = EuroMillionsBoxBottomAction;