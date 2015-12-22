var React = require('react');

var EuroMillionsAdvancedPlayBtn = new React.createClass({

    render : function () {
        return (
                    <a href="javascript:void(0);" onClick={this.props.click_advanced_play} className="btn big gwp advanced">Advanced Play
                        <svg className="ico v-clover"
                            dangerouslySetInnerHTML={{__html : '<use xlink:href="/w/svg/icon.svg#v-clover"></use>'}}/>
                    </a>
        )
    }
});

module.exports = EuroMillionsAdvancedPlayBtn;