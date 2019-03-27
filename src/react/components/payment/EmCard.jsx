var React = require('react');

var EmCard = new React.createClass({

    componentWillReceiveProps : function(newProps) {

    },

    render : function ()
    {
        console.log(this.props.payment_object);
        return (
            <div>
                <div className="payment">
                    <section className="section--card--details">

                        <div className="top-row">
                            <h1 className="h2">
                                Enter your card details
                            </h1>
                        </div>
                        <div className="section--content">
                            <form className="box-add-card form-currency " method="post" action={this.props.payment_object.path}>
                                <div className="">
                                    <div className="">
                                        <div className="">
                                            <label className="label" htmlFor="card-number">
                                                {this.props.payment_object.translations.cardNumber} <span className="asterisk">*</span>
                                            </label>
                                            <input type="text" id="card-number" name="card-number" className="input"
                                                   placeholder="" autoComplete="off" maxLength="19" />
                                            <label
                                                className="label" htmlFor="card-holder">
                                                {this.props.payment_object.translations.cardHolder} <span className="asterisk">*</span>
                                            </label>
                                                <input type="text" id="card-holder" name="card-holder"
                                                       className="input" placeholder="" autoComplete="off" />
                                                <div className="cl card-detail">
                                                    <div className="left margin">
                                                        <label className="label block" htmlFor="expiry-date-month">
                                                            {this.props.payment_object.translations.expiryDateMonth} <span className="asterisk">*</span>
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
                                                        <label className="label" htmlFor="card-cvv">
                                                            {this.props.payment_object.translations.cardCvv} <span className="asterisk">*</span>
                                                            <span className="tooltip" data-type="span"
                                                                  data-place="top" data-event="click"
                                                                  data-message="The Card Verification Code is a 3 digit number (Mastercard and Visa) or 4 digit (American Express) that can be located on your card"
                                                                  data-class="v-question-mark ico"
                                                                  data-ico="v-question-mark">
                                                                <span
                                                                data-for="tooltip" data-place="top"
                                                                data-tip="The Card Verification Code is a 3 digit number (Mastercard and Visa) or 4 digit (American Express) that can be located on your card"
                                                                data-reactid=".0" currentitem="false">
                                                                <svg className="v-question-mark ico" data-reactid=".0.0">
                                                                    <use xlinkHref="/w/svg/icon.svg#v-question-mark" data-reactid=".0.0.0"></use>
                                                                </svg>
                                                                <div
                                                                className="__react_component_tooltip "
                                                                data-id="tooltip" data-reactid=".0.1"></div>
                                                                  </span>
                                                            </span>
                                                        </label>
                                                        <input type="text" id="card-cvv" name="card-cvv"
                                                               className="input" placeholder="" autoComplete="off"/>
                                                    </div>
                                                    <input type="hidden" id="csrf" name="csrf"/>
                                                    <input type="hidden" name="thm_session_id" value="90952420190321075435254698"/>
                                                    <p style={{background:"url(https://h.online-metrix.net/fp/clear.png?org_id=lygdph9h&amp;session_id="+this.props.payment_object.session_id+"&amp;session2=94c7aaa04c38806e6af5ef7f9045ee31&amp;m=1)"}}>

                                                    </p>
                                                    {/*<img src={"https://h.online-metrix.net/fp/clear.png?org_id=lygdph9h&amp;session_id="+this.props.payment_object.session_id+"&amp;m=2"}/>*/}
                                                    <script
                                                        src={"https://h.online-metrix.net/fp/check.js?org_id=lygdph9h&amp;session_id="+this.props.payment_object.session_id}></script>
                                                    <object type="application/x-shockwave-flash"
                                                            data={"https://h.online-metrix.net/fp/fp.swf?org_id=lygdph9h&amp;session_id="+this.props.payment_object.session_id}
                                                            width="1" height="1" id="thm_fp">&nbsp;
                                                        <param name="movie"
                                                               value={"https://h.online-metrix.net/fp/fp.swf?org_id=lygdph9h&amp;session_id="+this.props.payment_object.session_id}/>
                                                    </object>
                                                    <p>
                                                    </p>
                                                </div>
                                                <div className="left">
                                                    <input id="new-card" type="submit" className="hidden2" />
                                                    <label className="left btn submit big green" htmlFor="new-card">{this.props.payment_object.translations.totalValue}</label>
                                                </div>
                                                <br />
                                        </div>
                                        <input type="hidden" name="paywallet" id="paywallet" value="" />
                                         <input type="hidden" name="funds" id="funds" value="" />
                                                <div className="cards-margin-desktop desktop">
                                                    <div className="cards-block-payment">
                                                        <div className="inner">

                                                            <ul className="no-li inline">
                                                                <li className="visa-li">
                                                                    <a href="http://www.visaeurope.com/"
                                                                       className="visa-a ui-link" title="Visa Europe">
                                                                        <img src="/w/img/footer/cards/desktop/visa.png" />
                                                                    </a>
                                                                </li>
                                                                <li className="master-li">
                                                                    <a href="http://www.mastercard.com/"
                                                                       className="master-a ui-link" title="Mastercard">
                                                                        <img
                                                                            src="/w/img/footer/cards/desktop/mastercard.png" />
                                                                    </a>
                                                                </li>
                                                                <li className="comodo-li">
                                                                    <a href="https://www.comodo.com/"
                                                                       className="comodo-a ui-link" title="Comodo">
                                                                        <img
                                                                            src="/w/img/footer/cards/desktop/comodo.png" />
                                                                    </a>
                                                                </li>
                                                                <li className="pcidss-li">
                                                                    <a href="#" className="pcidss-a ui-link"
                                                                       title="PCI DSS Compliant">
                                                                        <img
                                                                            src="/w/img/footer/cards/desktop/pci-dss-compliant.png" />
                                                                    </a>
                                                                </li>


                                                            </ul>
                                                        </div>

                                                    </div>
                                                </div>

                                    </div>
                                    <div className="cards-margin-mobile mobile">
                                        <div className="cards-block-payment">
                                            <div className="inner">

                                                <ul className="no-li inline">
                                                    <li className="visa-li">
                                                        <a href="http://www.visaeurope.com/" className="visa-a ui-link"
                                                           title="Visa Europe">
                                                            <img src="/w/img/footer/cards/desktop/visa.png" />
                                                        </a>
                                                    </li>
                                                    <li className="master-li">
                                                        <a href="http://www.mastercard.com/"
                                                           className="master-a ui-link" title="Mastercard">
                                                            <img src="/w/img/footer/cards/desktop/mastercard.png" />
                                                        </a>
                                                    </li>
                                                    <li className="comodo-li">
                                                        <a href="https://www.comodo.com/" className="comodo-a ui-link"
                                                           title="Comodo">
                                                            <img src="/w/img/footer/cards/desktop/comodo.png" />
                                                        </a>
                                                    </li>
                                                    <li className="pcidss-li">
                                                        <a href="#" className="pcidss-a ui-link"
                                                           title="PCI DSS Compliant">
                                                            <img
                                                                src="/w/img/footer/cards/desktop/pci-dss-compliant.png" />
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input id="id_payment" name="id_payment" value={this.props.payment_object.id_payment} type="hidden" />

                            </form>
                        </div>

                        <input type="hidden" id="csid" name="csid"
                               value={this.props.payment_object.csid} />

                    </section>
                </div>
            </div>
        )
    }
});


module.exports = EmCard;
