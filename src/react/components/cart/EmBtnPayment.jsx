var React = require('react');

var EmBtnPayment = new React.createClass({

    displayName: 'EmBtnPayment',


    render : function ()
    {
        var frequency = 1;
        if (this.props.config) {
            frequency = this.props.config.frequency;
        }

        if(this.props.databtn == 'wallet') {

            return (
                <div className="box-bottom cl">
                    <a href={this.props.href} data-btn={this.props.databtn} className={this.props.classBtn}>{this.props.text}</a>
                </div>
            )
        } else {

            return (

                <div className="box-bottom cl">
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
module.exports = EmBtnPayment;