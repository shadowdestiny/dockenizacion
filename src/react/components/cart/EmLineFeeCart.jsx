var React = require('react');

var EmAddFund = require('./EmAddFund.jsx');


var EmLineFeeCart = new React.createClass({


    getInitialState : function ()
    {
        return {
            show_addfund: false
        };

    },

    handleClick : function ()
    {
        this.setState( {show_addfund : true} );
    },


    render : function ()
    {
        var addFundComponent = null;
        if(this.state.show_addfund) {
            addFundComponent = <EmAddFund keyup_callback={this.props.keyup} show={this.state.show_addfund}/>;
        }

        var show_fee_text = null;
        if(this.props.show_fee_text) {
            show_fee_text = 'Fee for transactions below ' + this.props.price_below_fee + ' ' + this.props.currency_symbol;
        }
        var fee_value = this.props.currency_symbol + ' ' + this.props.fee_charge;
        if(!this.props.show_fee_value) {
            fee_value = '';
        }
        if(!this.props.show_all_fee){
            return null;
        }
        return (
            <div className="row cl">
                <div className="txt-fee">
                    {show_fee_text}
                </div>
                <div className="right tweak">
                    <div className="summary val">{fee_value}</div>
                        <div className="box-funds cl">
                            <a className="add-funds" onClick={this.handleClick} href="javascript:void(0)">Add Funds to avoid charges</a><br></br>
                            <div className="box-combo">
                                {addFundComponent}
                            </div>
                        </div>
                </div>

            </div>
        )
    }
});

module.exports = EmLineFeeCart;