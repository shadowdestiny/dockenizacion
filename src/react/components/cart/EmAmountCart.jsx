var React = require('react');
var ReactDOM = require('react-dom');


var EmAmountCart = new React.createClass({

    getInitialState : function ()
    {
        return {
            total : 0
        }
    },

    displayName: 'EmTotalCart',


    componentWillReceiveProps : function(newProps) {
        this.setState({
            total : newProps.pricetopay
        });
       //$(document).trigger("totalPriceEvent", [ newProps.pricetopay, newProps.funds ]);
    },

    render : function ()
    {
        return (
            <div className="row cl order-separator-container">
                <table className={"order-table-balance"}>
                    <tbody>
                    <tr>
                        <td>
                            <div className="summary text order-text-format">
                                {this.props.txt_amount}
                            </div>
                        </td>
                        <td className={"right"}>
                            <div className="summary val">
                                {this.state.total}
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        )
    }
});


module.exports = EmAmountCart;
