var React = require('react');
var EuroMillionsLine = require('./EmLine.js');


var EuroMillionsMultipleEmLines = React.createClass({

    getInitialState: function (){
        return { storage: JSON.parse(localStorage.getItem('bet_line')) || [],
                 numberLines : 0,
                 random_all : false,
                 count: 0
                };
    },

    componentDidMount: function(){
        var storage = this.state.storage;
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
                        current_count = storage.length;
                    }
                }
            });
        }
        this.setState( { storage : storage,
                         numberLines : current_count -1 });
    },

    addLinesInStorage : function (e, line, numbers, stars) {
        this.state.storage[line] = {
            'numbers': numbers,
            'stars': stars
        };
        localStorage.setItem('bet_line', JSON.stringify(this.state.storage, function(k,v){
                return (v == null) ? { 'numbers' : [], 'stars' : []} : v;
            }
        ));
    },

    render : function() {
        var numberEuroMillionsLine = this.props.numberEuroMillionsLine;
        var random = this.props.random_all;

        var em_lines = [];
        for (let i = 0; i <= numberEuroMillionsLine; i++) {
            em_lines.push(
                    <EuroMillionsLine clear_all={this.props.clear_all} random={random} addLineInStorage={this.addLinesInStorage} storage={this.state.storage[i]} callback={this.props.callback} numberPerLine="5" key={i} lineNumber={i}/>
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
