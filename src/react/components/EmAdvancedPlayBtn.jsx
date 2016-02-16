var React = require('react');

var EuroMillionsAdvancedPlayBtn = new React.createClass({

    render : function () {
        return (
            <span>
            <a href="javascript:void(0);" className="btn big rwr reset">Reset Advanced Settings <svg className="ico v-cross"><use xlinkHref="/w/svg/icon.svg#v-cross"></use></svg></a>
            <a href="javascript:void(0);" onClick={this.props.click_advanced_play} className="btn big gwp advanced">Advanced Play <svg className="ico v-clover"><use xlinkHref="/w/svg/icon.svg#v-clover"></use></svg>
            </a>
            </span>
        )
    }
});

module.exports = EuroMillionsAdvancedPlayBtn;