var React = require('react');

var EuroMillionsRandomAllBtn = new React.createClass({

    propTypes: {
        onBtnRandomAllClick : React.PropTypes.func.isRequired
    },

    render : function () {
        return (
            <li><a className="btn bwb random-all" onClick={this.props.onBtnRandomAllClick.bind(null, null)} href="javascript:void(0);">Randomize all lines

                <i className="ico ico-shuffle"></i></a></li>
        )
    }

});

module.exports = EuroMillionsRandomAllBtn;