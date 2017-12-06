var React = require('react');
var EuroMillionsClearAllBtn = new React.createClass({
    propTypes: {
        onBtnClearAllClick : React.PropTypes.func.isRequired
    },
    render : function () {
        if(!this.props.show_btn_clear) {
            return null
        }else{
            return(
                <li><a className="btn rwr random-all cal clear-all ui-link" onClick={this.props.onBtnClearAllClick.bind(null, null)} href="javascript:void(0);">{this.props.clearAllLines} <svg className="ico v-cross"><use xlinkHref="/w/svg/icon.svg#v-cross"></use></svg></a></li>
            )
        }
    }

});

module.exports = EuroMillionsClearAllBtn;
