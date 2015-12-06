var React = require('react');
var EuroMillionsAddLinesBtn = require('./EmAddLinesBtn.jsx');
var EuroMillionsRandomAllBtn = require('./EmRandomAllBtn.jsx');

var EuroMilliosnBoxActionPlay = React.createClass({

    handlerAddLines : function() {
        $(document).trigger('add_lines');
    },

    render : function() {
        var elem = [];

        elem.push(<EuroMillionsAddLinesBtn onBtnAddLinesClick={this.handlerAddLines} key="1"/>);
        elem.push(<EuroMillionsRandomAllBtn  key="2"/>);
        return (
            <ul className="no-li cl box-action">
                {elem}
            </ul>
        );
    }
})


    //<li class="box-more" data-tip="{{ language.translate('It is not possible to add more lines until you fill in the previous ones') }}"><a class="btn gwg add-more" href="javascript:void(0);">{{ language.translate("Add more lines") }} <i class="ico ico-plus"></i></a></li>
    //<li><a class="btn bwb random-all" href="javascript:void(0);">{{ language.translate("Randomize all lines") }} <i class="ico ico-shuffle"></i></a></li>
    //<li class="fix-margin"><a class="btn rwr clear-all" href="javascript:void(0);">{{ language.translate("Clear all lines") }} <i class="ico ico-cross"></i></a></li>

module.exports = EuroMilliosnBoxActionPlay;