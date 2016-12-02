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
            <div>
                <table>
                    <tbody>
                    <tr>
                        <td width="15px"><input type="radio" name="draw_type" onClick={this.props.sendLineSelected.bind(null, this.props.draws)} defaultChecked={this.props.checked} value={this.props.draws} /></td>
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