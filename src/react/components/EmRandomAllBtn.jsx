var React = require('react');
var EuroMillionsRandomAllBtn = new React.createClass({
    propTypes: {
        onBtnRandomAllClick : React.PropTypes.func.isRequired
    },
    render : function(){
        return(
            <li><a className="btn bwb random-all" onClick={this.props.onBtnRandomAllClick.bind(null, null)} href="javascript:void(0);">Randomize all lines <svg className="ico v-shuffle" dangerouslySetInnerHTML={{__html : '<use xlink:href="/w/svg/icon.svg#v-shuffle"></use>'}}/></a></li>
        )
    }

});

module.exports = EuroMillionsRandomAllBtn;