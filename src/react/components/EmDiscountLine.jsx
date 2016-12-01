var React = require('react');

var EuroMillionsDiscountLine = React.createClass({

    render: function () {
        var price = this.props.price;

        if (this.props.discount != 0) {
            price = this.props.price / ((this.props.discount / 100) + 1);
            price = price.toFixed(2);
        }

        return (
            <div>
                <table>
                    <tbody>
                    <tr>
                        <td width="15px"><input type="radio" name="draw_type" checked={this.props.checked} /></td>
                        <td width="200px">{this.props.desc}</td>
                        <td>{price}€ / {this.props.price_desc}</td>
                    </tr>
                    </tbody>
                </table>p
            </div>
        );
    }
});

module.exports = EuroMillionsDiscountLine;