var React = require('react');
var EuroMillionsRandomAllBtn = new React.createClass({
    propTypes: {
        onBtnRandomAllClick : React.PropTypes.func.isRequired
    },
    render : function(){
        return(
            <li><a className="btn bwb random-all" onClick={this.props.onBtnRandomAllClick.bind(null, null)} href="javascript:void(0);">{this.props.randomizeAllLines} <svg className="ico v-shuffle"><use xlinkHref="/w/svg/icon.svg#v-shuffle"></use></svg></a></li>
        )
    }
});

module.exports = EuroMillionsRandomAllBtn;