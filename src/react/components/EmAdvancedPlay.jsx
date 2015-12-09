var React = require('react');

var EuroMillionsAdvancedPlay = new React.createClass({

    render : function () {
        return (
                    <a href="javascript:void(0);" className="btn big gwp advanced">Advanced Play
                        <svg className="ico v-clover"
                            dangerouslySetInnerHTML={{__html : '<use xlink:href="/w/svg/icon.svg#v-clover"></use>'}}/>
                    </a>
        )
    }
});

module.exports = EuroMillionsAdvancedPlay;