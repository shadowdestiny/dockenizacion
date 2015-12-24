var React = require('react');
var EuroMillionsAddLinesBtn = require('./EmAddLinesBtn.jsx');
var EuroMillionsRandomAllBtn = require('./EmRandomAllBtn.jsx');
var EuroMillionsClearAllBtn = require('./EmClearAllBtn.jsx');

var EuroMilliosnBoxActionPlay = React.createClass({

    displayName : 'EuroMilliosnBoxActionPlay',

    handlerAddLines : function() {
        this.props.add_lines(null);
    },
    handlerRandomAll : function() {
        this.props.random_all_btn(null);

    },
    handlerClearAll : function () {
       this.props.clear_all_btn(null)
    },

    render : function() {
        var elem = [];
        var show_btn = false;
        this.props.lines.forEach(function (show_value) {
            if(show_value) {
                show_btn = true;
            }
        });
        elem.push(<EuroMillionsAddLinesBtn show_tooltip={this.props.show_tooltip}  mouse_over_btn={this.props.mouse_over_btn} onBtnAddLinesClick={this.handlerAddLines} key="1"/>);
        elem.push(<EuroMillionsRandomAllBtn onBtnRandomAllClick={this.handlerRandomAll} key="2"/>);
        elem.push(<EuroMillionsClearAllBtn show_btn_clear={show_btn} onBtnClearAllClick={this.handlerClearAll} key="3"/>);

        return (
            <div className="cl" id="box-action">
                <ul className="no-li cl box-action">
                    {elem}
                </ul>
            </div>
        );
    }
})

module.exports = EuroMilliosnBoxActionPlay;