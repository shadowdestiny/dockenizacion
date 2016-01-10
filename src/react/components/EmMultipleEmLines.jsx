var React = require('react');
var EuroMillionsLine = require('./EmLine.js');


var EuroMillionsMultipleEmLines = React.createClass({

    getInitialState: function (){
        return { storage: JSON.parse(localStorage.getItem('bet_line')) || [],
                 random_all : false,
                };
    },

    render : function() {
        var numberEuroMillionsLine = this.props.numberEuroMillionsLine;
        var random = this.props.random_all;
        var em_lines = [];

        for (let i = 0; i <= numberEuroMillionsLine; i++) {
            em_lines.push(
                    <EuroMillionsLine clear_all={this.props.clear_all} random={random} addLineInStorage={this.props.add_storage} storage={this.state.storage[i]} callback={this.props.callback} numberPerLine="5" key={i} lineNumber={i}/>
            );
        }
        return (
            <div className="box-lines cl" id="box-lines">
                {em_lines}
            </div>
        );
    }
})

module.exports = EuroMillionsMultipleEmLines;
