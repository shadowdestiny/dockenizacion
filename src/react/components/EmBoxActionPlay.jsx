import PowerPlayCheckbox from './PowerPlayCheckbox'
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
      const {
        translations,
        showPowerPlayCheck,
        enablePowerPlay,
        show_clear_all,
        addlines_message,
        show_tooltip,
        addLinesBtn,
        mouse_over_btn,
        randomizeAllLines,
        clearAllLines,
      } = this.props

        var elem = [];

        elem.push(<EuroMillionsAddLinesBtn addlines_message={addlines_message} show_tooltip={show_tooltip} addLinesBtn={addLinesBtn} mouse_over_btn={mouse_over_btn} onBtnAddLinesClick={this.handlerAddLines} key="1"/>);
        elem.push(<EuroMillionsRandomAllBtn onBtnRandomAllClick={this.handlerRandomAll} randomizeAllLines={randomizeAllLines} key="2"/>);
        elem.push(<EuroMillionsClearAllBtn clear_btn={clear_btn} show_btn_clear={show_clear_all} clearAllLines={clearAllLines} onBtnClearAllClick={this.handlerClearAll} key="3"/>);

        return (
            <div className="cl" id="box-action">
              {showPowerPlayCheck
                ? <div className="left-box-action">
                    <PowerPlayCheckbox translations={translations} onChange={(checked) => enablePowerPlay(checked)} />
                  </div>
                : null
              }
                <ul className="no-li cl box-action">
                    {elem}
                </ul>
            </div>
        );
    }
})

module.exports = EuroMilliosnBoxActionPlay;
