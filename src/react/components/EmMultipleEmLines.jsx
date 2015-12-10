var React = require('react');
var EuroMillionsLine = require('./EmLine.js');


var EuroMillionsMultipleEmLines = React.createClass({

    getInitialState: function (){
        return { storage: [], numberLines : 0, random_all : false, count: 0 };
    },

    componentDidMount: function(){
        $(document).on('add_lines',function(e) {
            this.state.numberLines = this.state.numberLines + this.props.numberEuroMillionsLine +1;
            this.setState(this.state);
        }.bind(this));
        $(document).on('random_all_lines',function(e) {
            this.state.random_all = true;
            this.setState(this.state);
        }.bind(this));

        var storage = JSON.parse(localStorage.getItem('bet_line'));
        var current_count = this.props.numberEuroMillionsLine;
        if( storage != null ) {
            storage.forEach(function(obj,i) {
                if(obj != null) {
                    if(obj.numbers.length === 0 || obj.stars.length === 0) {
                        obj.numbers = [];
                        obj.stars = [];
                        storage[i] = obj;
                        localStorage.setItem('bet_line', JSON.stringify(storage));
                    } else if(i > (current_count) && current_count < storage.length) {
                        current_count = storage.length ;
                    }
                }
            });
        }
        this.state.numberLines = current_count -1;
        this.setState(this.state);
    },

    render : function() {

        if(this.state.numberLines < this.props.numberEuroMillionsLine) {
            this.state.numberLines = this.props.numberEuroMillionsLine;
        }

        var numberEuroMillionsLine = this.state.numberLines;
        var isAnimate = this.state.random_all;
        var em_lines = [];
        for (var i = 0; i <= numberEuroMillionsLine; i++) {
            em_lines.push(
                    <EuroMillionsLine animate={isAnimate} storage={this.state.storage} numberPerLine="5" key={i} lineNumber={i}/>
            );
        }
        return (
            <div>
              {em_lines}
            </div>
        );

    }
})

module.exports = EuroMillionsMultipleEmLines;
