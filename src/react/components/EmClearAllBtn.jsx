var React = require('react');

var EuroMillionsClearAllBtn = new React.createClass({

    propTypes: {
        onBtnClearAllClick : React.PropTypes.func.isRequired
    },

    render : function () {

        if(!this.props.show_btn_clear) {
            return null
        }else {
            return (
                <li>
                    <a className="btn rwr clear-all" onClick={this.props.onBtnClearAllClick.bind(null, null)} href="javascript:void(0);">Clear all lines
                        <i className="ico ico-cross"></i>
                    </a>
                </li>
            )

        }
    }

});

module.exports = EuroMillionsClearAllBtn;