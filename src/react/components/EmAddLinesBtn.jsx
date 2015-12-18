var React = require('react');
var ReactTooltip = require("react-tooltip")

var EuroMillionsAddLinesBtn = React.createClass({


    getInitialState : function ()
    {
        return {
            leave_tooltip : false
        }
    },

    mouseLeave : function() {
      //  this.state.leave_tooltip = true;
    },

    componentDidMount : function () {

    },

    propTypes: {
        onBtnAddLinesClick : React.PropTypes.func.isRequired
    },

    render: function() {

        var react_tooltip = <ReactTooltip type="light" id='add-lines'/>;
        if( !this.props.show_tooltip) {
            react_tooltip  = null;
        }
    //    if(this.state.leave_tooltip) react_tooltip = null;
        return (
            <li data-for="add-lines" onTouchStart={this.props.onBtnAddLinesClick} onMouseOut={this.mouseLeave} onMouseOver={this.props.mouse_over_btn.bind(null, null)} className="box-more" data-tip="It is not possible to add more lines until you fill in the previous ones"><a className="btn gwg add-more" onTouchEnd={this.props.onBtnAddLinesClick.bind(null, null)} onClick={this.props.onBtnAddLinesClick.bind(null, null)} href="javascript:void(0);">Add more lines
                <svg className="ico v-plus"
                     dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-plus"></use>'}}/>
            </a>
                {react_tooltip}
            </li>
        )
    }
});

module.exports = EuroMillionsAddLinesBtn;