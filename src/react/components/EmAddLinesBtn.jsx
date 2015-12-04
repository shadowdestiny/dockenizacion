var React = require('react');

var EuroMillionsAddLinesBtn = React.createClass({
    render: function() {
        var showTip = this.props.showtip;
        if(showTip) {

        }
        return (
            <li className="box-more" data-tip=""><a className="btn gwg add-more" href="javascript:void(0);">Add more lines
                <svg className="ico v-plus"
                     dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-plus"></use>'}}/>
            </a></li>
        );
    }
});

module.exports = EuroMillionsAddLinesBtn;