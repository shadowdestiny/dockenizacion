var React = require('react');
var EuroMillionsAddLinesBtn = require('./EmAddLinesBtn.jsx');
var EuroMillionsRandomAllBtn = require('./EmRandomAllBtn.jsx');
var EuroMillionsClearAllBtn = require('./EmClearAllBtn.jsx');

var EuroMilliosnBoxActionPlay = React.createClass({


    getInitialState: function() {
      return {
          lines : [],
          show_btn_clear: false
      }
    },

    componentDidMount: function(){
        //EMTD surely, should be move it to another place
        $(document).on('show_btn_clear',function(e, line, show) {
            this.state.lines[line] = show;
            this.setState(this.state);
        }.bind(this));
    },

    handlerAddLines : function() {
        $(document).trigger('add_lines');
    },
    handlerRandomAll : function() {
        $(document).trigger('random_all_lines');
    },
    handlerClearAll : function () {
        $(document).trigger('clear_line');
        this.state.show_btn_clear = false;
        this.state.lines = [];
        this.setState(this.state);
    },

    render : function() {
        var elem = [];
        var show_btn = false;
        this.state.lines.forEach(function (show_value) {
            if(show_value) {
                show_btn = true;
            }
        });
        this.state.show_btn_clear = show_btn;
        var show_btn_clear = this.state.show_btn_clear;
        elem.push(<EuroMillionsAddLinesBtn onBtnAddLinesClick={this.handlerAddLines} key="1"/>);
        elem.push(<EuroMillionsRandomAllBtn onBtnRandomAllClick={this.handlerRandomAll} key="2"/>);
        elem.push(<EuroMillionsClearAllBtn show_btn_clear={show_btn_clear} onBtnClearAllClick={this.handlerClearAll} key="3"/>);

        return (
            <ul className="no-li cl box-action">
                {elem}
            </ul>
        );
    }
})

module.exports = EuroMilliosnBoxActionPlay;