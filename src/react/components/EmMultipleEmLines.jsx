var React = require('react');
var EuroMillionsLine = require('./EmLine.js');


var EuroMillionsMultipleEmLines = React.createClass({

    getInitialState: function (){
        return {
                 random_all : false,
                };
    },

    render : function() {
        var numberEuroMillionsLine = this.props.numberEuroMillionsLine;
        var random = this.props.random_all;
        var em_lines = [];
        for (let i = 0; i < numberEuroMillionsLine; i++) {
            em_lines.push(
                    <EuroMillionsLine
                      clear_all={this.props.clear_all}
                      random={random}
                      addLineInStorage={this.props.add_storage}
                      storage={this.props.storage[i]}
                      callback={this.props.callback}
                      numberPerLine="5"
                      starsPerLine={4}
                      key={i}
                      lineNumber={i}
                      txtLine={this.props.txtLine}
                      gameMode={this.props.gameMode}
                      translations={this.props.translations}
                    />
            );
        }
        var date2 = new Date();
        return (
            <div className="box-lines cl" id="box-lines">
                {em_lines}
            </div>
        );
    }
})

module.exports = EuroMillionsMultipleEmLines;
