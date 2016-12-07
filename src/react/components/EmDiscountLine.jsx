var React = require('react');

var EuroMillionsDiscountLine = React.createClass({

    render: function () {
        var price = this.props.price;

        if (this.props.discount != 0) {
            price = this.props.price / ((this.props.discount / 100) + 1);
            price = price.toFixed(2);
        }
        var classBtn = (this.props.checked) ? 'btn pwp add-more ui-link pwp-active' : 'btn pwp add-more ui-link';
        return (
            <div className="buttonDrawList">
                <a className={classBtn} href="javascript:void(0);" onClick={this.props.sendLineSelected.bind(null, this.props.draws, this.props.discount)}>
                    <span>
                        {this.props.desc}
                    </span>
                </a>
                &nbsp; {price} {this.props.currency_symbol} / {this.props.price_desc}
            </div>
        );
    }
});

module.exports = EuroMillionsDiscountLine;