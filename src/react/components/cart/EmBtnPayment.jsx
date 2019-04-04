var React = require('react');

var EmBtnPayment = new React.createClass({

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
                    <a href={this.props.href} data-btn={this.props.databtn} className={this.props.classBtn}>
                        {this.props.text}
                    </a>
                </div>
            )
        } else {
            const pay = this.props.text;
            return (
                <div className="box-bottom cl" style={{opacity : style}}>
                    <a href={this.props.href} data-btn='wallet' className={this.props.classBtn}>
                        {pay}
                    </a>
                </div>
            )
        }
    }
});
module.exports = EmBtnPayment;