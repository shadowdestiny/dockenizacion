var React = require('react');

var EuroMillionsAddLinesBtn = React.createClass({


    mouseOver : function() {

    },

    componentDidMount : function () {

    },

    propTypes: {
        onBtnAddLinesClick : React.PropTypes.func.isRequired
    },

    render: function() {
        return (
            <li onMouseOver={this.props.mouse_over_btn.bind(null, null)} onTouchStart={this.mouseOver} className="box-more" data-tip="It is not possible to add more lines until you fill in the previous ones"><a className="btn gwg add-more" onClick={this.props.onBtnAddLinesClick.bind(null, null)} href="javascript:void(0);">Add more lines
                <svg className="ico v-plus"
                     dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-plus"></use>'}}/>
            </a></li>
        );
    }
});

module.exports = EuroMillionsAddLinesBtn;