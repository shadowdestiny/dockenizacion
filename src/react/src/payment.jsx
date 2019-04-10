import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';

/**/
import EmOptionSelector from '../components/payment/EmOptionSelector';
import EmCard from '../components/payment/EmCard';
import MoneyMatrix from "../components/payment/moneymatrix";

class Payment extends Component {

    constructor(props) {
        super(props);
        this.state = {
            typePayment : 1,
            mountValue  : "",
            valueClean  : "",
            csid        : "",
            moneymatrix_url : "",
        }
    }

    componentWillMount() {
        let self = this;
        $(document).on("refreshValuePayment",{value: 0.00,value_clean:0.00,csid:''},function(e, value,value_clean,csid) {
           self.setState({
               mountValue : value,
               valueClean : value_clean,
               csid
           });

            axios.post('/ajax/funds/order',{amount:value_clean}).then((response) => {
                self.setState({
                    moneymatrix_url : response.data.cashier.cashierUrl
                });
            });
        });
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
                            funds_value={this.state.valueClean}
                            csid={this.state.csid}
                    />
                    : <MoneyMatrix moneymatrix_url={this.state.moneymatrix_url}/>
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
/>,
document.getElementById('payment-section-wallet'));
