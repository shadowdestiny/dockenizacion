var React = require('react');
var EuroMillionsLine = require('./EmLine.js');


var EuroMillionsMultipleEmLines = React.createClass({

    getInitialState: function (){
        return {numberLines : 0, animate : false, count: 0, clearAll : false};
    },

    componentDidMount: function(){

        $(document).on('add_lines',function(e) {
            this.state.numberLines = this.state.numberLines + this.props.numberEuroMillionsLine +1;
            this.setState(this.state);
        }.bind(this));

        $(document).on('random_all_lines',function(e) {
            this.state.animate = true;
            this.setState(this.state);
        }.bind(this));
    },
    render : function() {

        if(this.state.numberLines < this.props.numberEuroMillionsLine) {
            this.state.numberLines = this.props.numberEuroMillionsLine;
        }

        var numberEuroMillionsLine = this.state.numberLines;
        var isAnimate = this.state.animate;
        var storage = [];
        var em_lines = [];
        for (var i = 0; i <= numberEuroMillionsLine; i++) {
            em_lines.push(
                    <EuroMillionsLine animate={isAnimate} storage={storage} numberPerLine="5" key={i} lineNumber={i}/>
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
