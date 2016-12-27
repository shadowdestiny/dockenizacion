var React = require('react');

var EuroMillionsDiscountLine = React.createClass({

    render: function () {
        var price = this.props.price * this.props.multi_price / 100;

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
                    &nbsp; {price.toFixed(2)} {this.props.currency_symbol} / {this.props.price_desc}
                </div>
            </div>
        );
    }
});

module.exports = EuroMillionsDiscountLine;