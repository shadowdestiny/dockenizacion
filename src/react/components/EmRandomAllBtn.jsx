var React = require('react');

var EuroMillionsRandomAllBtn = new React.createClass({

    render : function () {
        return (
            <li><a className="btn bwb random-all" href="javascript:void(0);">Randomize all lines
                <i className="ico ico-shuffle"></i></a></li>
        )
    }

});

module.exports = EuroMillionsRandomAllBtn;