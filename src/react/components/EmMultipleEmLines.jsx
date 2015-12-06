var React = require('react');
var EuroMillionsLine = require('./EmLine.js');


var EuroMillionsMultipleEmLines = React.createClass({

    getInitialState: function (){
        return {numberLines : 0, count: 0};
    },

    componentDidMount: function(){
        $(document).on('add_lines',function(e) {
            this.state.numberLines = this.state.numberLines + this.props.numberEuroMillionsLine +1;
            this.setState(this.state);
        }.bind(this));
    },
    render : function() {

        if(this.state.numberLines < this.props.numberEuroMillionsLine) {
            this.state.numberLines = this.props.numberEuroMillionsLine;
        }
        var numberEuroMillionsLine = this.state.numberLines;

        var storage = [];
        var em_lines = [];
        for (var i = 0; i <= numberEuroMillionsLine; i++) {
            em_lines.push(
                    <EuroMillionsLine storage={storage} numberPerLine="5" key={i} lineNumber={i}/>
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
