var React = require('react');
var  PropTypes = require ('prop-types');
var createReactClass = require('create-react-class');
var EuroMillionsClearLine = createReactClass({

    getDefaultProps: function() {
        return {
            showed : false
        }
    },
    propTypes: {
        onClearClick : PropTypes.element.isRequired,
    },
    render: function () {
        var style = this.props.showed ? "visible" : "hidden";
        return (
            <a className="clear btn gwr" style={{visibility : style}} onClick={this.props.onClearClick.bind(null,null)} href="javascript:void(0);"><span>{this.props.clear_btn} </span><svg className="ico v-cross"><use xlinkHref="/w/svg/icon.svg#v-cross"></use></svg>
            </a>
        );
    }
});

//module.exports = EuroMillionsClearLine;
export default EuroMillionsClearLine;