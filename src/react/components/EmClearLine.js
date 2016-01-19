var React = require('react');

var EuroMillionsClearLine = React.createClass({

    getDefaultProps: function() {
        return {
            showed : false
        }
    },
    propTypes: {
        onClearClick : React.PropTypes.func.isRequired,
    },
    render: function () {
        var style = this.props.showed ? "visible" : "hidden";
        return (
            <a className="clear btn gwr" style={{visibility : style}} onClick={this.props.onClearClick.bind(null,null)} href="javascript:void(0);">Clear <svg className="ico v-cross"><use xlinkHref="/w/svg/icon.svg#v-cross"></use></svg>
            </a>
        );
    }
});

module.exports = EuroMillionsClearLine;