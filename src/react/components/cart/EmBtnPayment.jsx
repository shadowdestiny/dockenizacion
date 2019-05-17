var React = require('react');
var createReactClass = require('create-react-class');
var EmBtnPayment = createReactClass({

    displayName: 'EmBtnPayment',

    render : function ()
    {
        var frequency = 1;
        var style = '';
        if(this.props.moneymatrixiframeloading == true)
        {
          //  href = '#';
            style = '0.3';
        }
        if (this.props.config) {
            frequency = this.props.config.frequency;
        }

        if(this.props.databtn == 'wallet') {

            return (
                <div className="box-bottom cl" style={{opacity: style}}>
                    <a href={this.props.href} data-btn={this.props.databtn}
                       className={this.props.classBtn}>{this.props.text}</a>
                </div>
            )
        } else {
            return (
                <div className="box-bottom cl" style={{opacity : style}}>
                    <a href={this.props.href} data-btn={this.props.databtn} className={this.props.classBtn}>
                        {this.props.text}
                        <span className="gap">
                           |
                        </span>
                        {this.props.price}
                    </a>
                </div>
            )
        }
    }
});
export default  EmBtnPayment;