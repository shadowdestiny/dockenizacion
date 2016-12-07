var React = require('react');

var EuroMillionsDiscountLine = React.createClass({

    //
    // propTypes: {
    //     sendLineSelected: React.PropTypes.func.isRequired
    // },


    isActive:function(value){
        return 'btn pwp add-more ui-link' + ((value=='active') ? ' pwp-active' : '');
    },

    getId:function(value){
        return 'buttonDrawKey' + value;
    },

    render: function () {
        var price = this.props.price;

        if (this.props.discount != 0) {
            price = this.props.price / ((this.props.discount / 100) + 1);
            price = price.toFixed(2);
        }


        return (
            <div className="buttonDrawList">
                <a id={this.getId(this.props.draws)} className={this.isActive(this.props.checked)} href="javascript:void(0);" onClick={this.props.sendLineSelected.bind(null, this.props.draws, this.props.discount, this.getId(this.props.draws))}>
                    <span>
                        {this.props.desc}
                    </span>
                </a>
                &nbsp; {price} {this.props.currency_symbol} / {this.props.price_desc}
            </div>
        );
    }
});

module.exports = EuroMillionsDiscountLine;