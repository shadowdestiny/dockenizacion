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
        if( !this.props.showed ) {
            return null;
        } else {
            return (
                <a className="clear btn gwr" onClick={this.props.onClearClick.bind(null,null)} href="javascript:void(0);">
                    Clear<svg className="ico v-cross"
                               dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-cross"></use>'}}/>
                </a>
            );
        }
    }
});

module.exports = EuroMillionsClearLine;