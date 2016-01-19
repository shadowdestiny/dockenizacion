var React = require('react');

var EuroMillionsRandomBtn = React.createClass({

    propTypes: {
        line: React.PropTypes.number.isRequired,
        onBtnRandomClick: React.PropTypes.func.isRequired
    },

    render : function() {
        return (
            <div className="col6 not random">
                <a className="btn gwy multiplay" onClick={this.props.onBtnRandomClick.bind(null, this.props.line)} href="javascript:void(0);">
                    <svg className="v-shuffle"><use xlinkHref="/w/svg/icon.svg#v-shuffle"></use></svg>
                </a>
            </div>
        );
    }

});

module.exports = EuroMillionsRandomBtn;