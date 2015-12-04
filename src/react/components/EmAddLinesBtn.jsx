var React = require('react');

var EuroMillionsAddLinesBtn = React.createClass({
    render: function() {
        var showTip = this.props.showtip;
        if(showTip) {

        }
        return (
            <li class="box-more" data-tip="{{ language.translate('It is not possible to add more lines until you fill in the previous ones') }}"><a class="btn gwg add-more" href="javascript:void(0);">Add more lines<svg class="ico v-plus"><use xlink:href="/w/svg/icon.svg#v-plus"></use></svg>
            </a></li>
        );
    }
});

module.exports = EuroMillionsAddLinesBtn;