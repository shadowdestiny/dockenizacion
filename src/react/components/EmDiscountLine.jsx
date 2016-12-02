var React = require('react');

var EuroMillionsDiscountLine = React.createClass({

    //
    // propTypes: {
    //     sendLineSelected: React.PropTypes.func.isRequired
    // },

    render: function () {
        var price = this.props.price;

        if (this.props.discount != 0) {
            price = this.props.price / ((this.props.discount / 100) + 1);
            price = price.toFixed(2);
        }

        return (
            <li className="discount-list">
                <input id={this.props.key} type="radio" name="draw_type" onClick={this.props.sendLineSelected.bind(null, this.props.draws)} defaultChecked={this.props.checked} value={this.props.draws} />
                <label htmlFor={this.props.key}>
                    {this.props.desc}
                </label>
                {price}€ / {this.props.price_desc}
            </li>
        );
    }
});

module.exports = EuroMillionsDiscountLine;