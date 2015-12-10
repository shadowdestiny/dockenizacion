var React = require('react');
var EuroMillionsLine = require('./EmLine.js');


var EuroMillionsMultipleEmLines = React.createClass({

    getInitialState: function (){
        return { storage: [], numberLines : 0, random_all : false, count: 0 };
    },

    componentDidMount: function(){
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
        var numberEuroMillionsLine = this.props.numberEuroMillionsLine;
        var random = this.props.random_all;
        var em_lines = [];
        for (var i = 0; i <= numberEuroMillionsLine; i++) {
            em_lines.push(
                    <EuroMillionsLine random={random} callback={this.props.callback} storage={this.state.storage} numberPerLine="5" key={i} lineNumber={i}/>
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
