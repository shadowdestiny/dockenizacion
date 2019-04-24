var React = require('react');
import MaskInput from 'react-maskinput';
var EmCard = new React.createClass({

    getInitialState : function ()
    {
        return {
           typeCard : 'card-number-icon',
           number   : ''
        }
    },

    componentWillReceiveProps : function(newProps) {

    },

    onChange: function(e) {
        let num = '';
        let typeCard = 'card-number-icon';
        if (e.target.value.length >= 1){
            num = e.target.value[0];
            if (num === '4'){
                typeCard += ' visa'
            } else {
                typeCard += ' master'
            }
        }
        this.setState({
            typeCard,
            number : e.target.value.replace(new RegExp(' ', 'g'), '')
        });
    },

    render : function ()
    {
        console.log(this.props.payment_object);
        return (
            <div>
                <div className="payment">
                    <section className="section--card--details">

                        <div className="section--content">
                            <form className="box-add-card form-currency " method="post" action={this.props.payment_object.path}>
                                <div className="">
                                    <div className="content-card">
                                        <div className="inputs-card-columns">
                                            <div className="1 inputs--card-column" style={{width:"60%"}}>
                                                <label className="label" htmlFor="card-number">
                                                    {this.props.payment_object.translations.cardNumber}
                                                </label>

                                                <MaskInput type="text" maskChar=" " mask="0000 0000 0000 0000" className={"input "+this.state.typeCard}
                                                           placeholder="" autoComplete="off" maxLength="19" onChange={this.onChange}/>

                                                <input type={"hidden"} value={this.state.number} id="card-number" name="card-number" />

                                                <label
                                                    className="label" htmlFor="card-holder">
                                                    {this.props.payment_object.translations.cardHolder}
                                                </label>
                                                <input type="text" id="card-holder" name="card-holder"
                                                       className="input" placeholder="" autoComplete="off" />
                                            </div>
                                            <div className="2 inputs--card-column" style={{width:"40%"}}>
                                                <div className="cl card-detail">
                                                    <div className="left margin">
                                                        <label className="label block" htmlFor="expiry-date-month">
                                                            {this.props.payment_object.translations.expiryDateMonth}
                                                        </label>
                                                        <input type="text" id="expiry-date-month"
                                                               name="expiry-date-month" className="input date"
                                                               placeholder="mm" autoComplete="off" maxLength="2"
                                                               style={{width:"14%"}}
                                                        />
                                                        <input type="text"
                                                               id="expiry-date-year"
                                                               name="expiry-date-year"
                                                               className="input date"
                                                               placeholder="yy"
                                                               autoComplete="off"
                                                               maxLength="2"
                                                               style={{width:"14%"}}
                                                        />
                                                    </div>
                                                    <div className="left cvv">
                                                        <div className={"rows"}>
                                                            <div className={"col"}>
                                                                <label className="label" htmlFor="card-cvv">
                                                                    {this.props.payment_object.translations.cardCvv}
                                                                </label>
                                                                <input type="text" id="card-cvv" name="card-cvv"
                                                                       className="input" placeholder="" autoComplete="off"/>
                                                            </div>
                                                            <div className={"col"} style={{textAlign:"left"}}>
                                                                <img className={"default-img ccv"} src="/w/img/review-and-pay/desktop/ccv.png"/>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <input type="hidden" id="csrf" name="csrf"/>
                                                    <input type="hidden" name="thm_session_id" value={this.props.payment_object.session_id}/>

                                                    <p>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="card-rows">
                                    <div className={"card-row left"}>
                                        <div>
                                            <span className={"card-text"}>
                                                By purchasing you agree to our
                                                <span className={"term-condition"}>
                                                    &nbsp;Terms & Conditions
                                                </span>
                                            </span>
                                        </div>
                                        <div className={"section-card-left"}>
                                            <div className={"section-card-left-cols left"}>
                                                <span className={"card-text"}>
                                                    Valid payment methods
                                                </span>
                                            </div>
                                            <div className={"section-card-left-cols right"}>
                                                <ul className="no-li inline">
                                                    <li className="master-li">
                                                        <a href="http://www.mastercard.com/"
                                                           className="master-a ui-link" title="Mastercard">
                                                            <img src="/w/img/review-and-pay/desktop/mastercard2.0.png"/>
                                                        </a>
                                                    </li>
                                                    <li className="visa-li">
                                                        <a href="http://www.visaeurope.com/" className="visa-a ui-link"
                                                           title="Visa Europe">
                                                            <img src="/w/img/review-and-pay/desktop/visa2.0.png"/>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div className={"card-row right"}>
                                        <input id="new-card" type="submit" className="hidden2" />
                                        <label className="left btn submit big green" htmlFor="new-card">{this.props.txt_deposit_buy_btn}&nbsp;{this.props.pricetopay}</label>
                                    </div>
                                </div>

                                <br />
                                <input type="hidden" name="paywallet" id="paywallet" value="" />
                                <input type="hidden" name="funds" id="funds" value="" />
                                <input type="hidden" name="funds-value" value={this.props.funds_value} />

                                <input id="id_payment" name="id_payment" value={this.props.payment_object.id_payment} type="hidden" />
                                <input type="hidden" id="csid" name="csid" value={this.props.csid} />

                            </form>
                        </div>


                    </section>
                </div>
            </div>
        )
    }
});


module.exports = EmCard;
