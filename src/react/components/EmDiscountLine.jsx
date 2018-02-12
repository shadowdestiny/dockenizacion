var React = require('react');

var EuroMillionsDiscountLine = React.createClass({

    render: function () {
        var price = this.props.price * this.props.multi_price / 100;

        var classBtn = (this.props.checked) ? 'ui-link pwp-active' : 'ui-link';
        return (
            <div className="button-draw-list">
                <a className={classBtn} href="javascript:void(0);" onClick={this.props.sendLineSelected.bind(null, this.props.draws, this.props.discount)}>
                    <span className="top">{this.props.desc}</span>
                    <span className="bottom">
                        {this.props.next_draw}{price}{this.props.price_desc}
                    </span>
                </a>
            </div>
        );
    }
});

module.exports = EuroMillionsDiscountLine;
