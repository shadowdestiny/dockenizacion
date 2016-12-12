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
            <div className="button-draw-list">
                <div className="box-button-draw-left">
                    <a className={classBtn} href="javascript:void(0);" onClick={this.props.sendLineSelected.bind(null, this.props.draws, this.props.discount)}>
                        <span>
                            {this.props.desc}
                        </span>
                    </a>
                </div>
                <div className="box-button-draw-right">
                    &nbsp; {price} {this.props.currency_symbol} / {this.props.price_desc}
                </div>
            </div>
        );
    }
});

module.exports = EuroMillionsDiscountLine;