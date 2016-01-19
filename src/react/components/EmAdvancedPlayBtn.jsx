var React = require('react');

var EuroMillionsAdvancedPlayBtn = new React.createClass({

    render : function () {
        return (
            <a href="javascript:void(0);" onClick={this.props.click_advanced_play} className="btn big gwp advanced">Advanced Play <svg className="ico v-clover"><use xlinkHref="/w/svg/icon.svg#v-clover"></use></svg>
            </a>
        )
    }
});

module.exports = EuroMillionsAdvancedPlayBtn;