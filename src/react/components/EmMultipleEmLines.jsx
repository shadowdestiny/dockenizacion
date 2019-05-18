var React = require('react');
import EuroMillionsLine from './EmLine.js';
const GAME_MODE_EUROJACKPOT = 'eurojackpot'
var createReactClass = require('create-react-class');
var EuroMillionsMultipleEmLines = createReactClass({

    getInitialState: function (){
        return {
                 random_all : false,
                };
    },

    render : function() {
        var numberEuroMillionsLine = this.props.numberEuroMillionsLine;
        var random = this.props.random_all;
        var em_lines = [];
        const { gameMode } = this.props
        for (let i = 0; i < numberEuroMillionsLine; i++) {
            em_lines.push(
                    <EuroMillionsLine
                      clear_all={this.props.clear_all}
                      random={random}
                      addLineInStorage={this.props.add_storage}
                      storage={this.props.storage[i]}
                      callback={this.props.callback}
                      numberPerLine="5"
                      starsPerLine={gameMode == GAME_MODE_EUROJACKPOT ? 5 : 4}
                      key={i}
                      lineNumber={i}
                      txtLine={this.props.txtLine}
                      gameMode={gameMode}
                      translations={this.props.translations}
                      defaultRandom={i===0}
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

// module.exports = EuroMillionsMultipleEmLines;
export default EuroMillionsMultipleEmLines;
