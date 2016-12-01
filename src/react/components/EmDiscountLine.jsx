var React = require('react');

var EuroMillionsDiscountLine = React.createClass({

    render: function () {
        var price;

        if (this.props.discount != 0) {
            price = this.props.price / ((this.props.discount / 100) + 1);
            price = price.toFixed(2);
        } else {
            price = this.props.price;
        }

        return (
            <div>
                <table>
                    <tbody>
                    <tr>
                        <td width="15px"><input type="checkbox" /></td>
                        <td width="200px">{this.props.desc}</td>
                        <td>{price}€ / {this.props.price_desc}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        );
    }
});

module.exports = EuroMillionsDiscountLine;