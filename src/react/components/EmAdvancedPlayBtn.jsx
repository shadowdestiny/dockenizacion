var React = require('react');

var EuroMillionsAdvancedPlayBtn = new React.createClass({

    getInitialState : function () {
        return ({
            is_changed : false
        })
    },

    componentWillReceiveProps : function (nextProps) {
        this.setState( { is_changed : nextProps.config_changed } )
    },

    render : function () {

        if(this.state.is_changed) {
            return (
                <span>
                    <a href="javascript:void(0);" onClick={this.props.reset} className="btn big rwr reset">Remove Advanced Settings <svg className="ico v-cross"><use xlinkHref="/w/svg/icon.svg#v-cross"></use></svg></a>
                </span>
            )
        } else {
            return (
                <span>
                    <a href="javascript:void(0);" onClick={this.props.click_advanced_play} className="btn big gwp advanced">Advanced Play <svg className="ico v-clover"><use xlinkHref="/w/svg/icon.svg#v-clover"></use></svg></a>
                </span>
            )
        }
    }
});

module.exports = EuroMillionsAdvancedPlayBtn;