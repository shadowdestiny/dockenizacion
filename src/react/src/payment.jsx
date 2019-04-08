import React, {Component} from 'react';
import ReactDOM from 'react-dom';

/**/
import EmOptionSelector from '../components/payment/EmOptionSelector';
import EmCard from '../components/payment/EmCard';

class Payment extends Component {

    constructor(props) {
        super(props);
        this.state = {
            typePayment : 1,
            mountValue  : "",
        }
    }

    componentWillMount() {
        let self = this;
        $(document).on("refreshValuePayment",{value: 0.00},function(e, value) {
           self.setState({
               mountValue : value
           })
        });
    }

    callback(){

    }

    selectTypePayment = (option) => {
        this.setState({typePayment : option});
    }

    render() {

        return (

            <div className=''>
                <EmOptionSelector
                    section_payment={false}
                    option_select_callback={this.selectTypePayment}
                />
                {this.state.typePayment === 1 ?
                    <EmCard payment_object={this.props.payment_object}
                            pricetopay={this.state.mountValue}
                            txt_deposit_buy_btn={this.props.txt_deposit_buy_btn}
                    />
                    : "-- section money matrix --"
                }
            </div>

        );
    }
}

//export default PaymentSection;
module.exports = Payment;

ReactDOM.render(<Payment
  payment_object={payment_object}
  txt_deposit_buy_btn={txt_depositBuy_btn}
  txt_value={txt_value}
/>,
document.getElementById('payment-section-wallet'));
