var React = require('react');

var EmOptionSelector = new React.createClass({

    getInitialState : function ()
    {
        return {
            radio_option_1 : 'selected',
            radio_option_2 : '',
        }
    },

    componentWillReceiveProps : function(newProps) {

    },

    selectRadio : function(num_option){
        if (num_option === 1){
            this.setState({
                radio_option_1 : 'selected',
                radio_option_2 : '',
            })
        } else {
            this.setState({
                radio_option_1 : '',
                radio_option_2 : 'selected',
            })
        }
        this.props.option_select_callback(num_option);
    },

    render : function ()
    {
        return (
            <div className={"emOptionsSelector"}>
                <section className={"section--card--details"}>

                    { this.props.section_payment===true ?
                        <div className="top-row">
                            <h1 className="h2">
                                PAYMENT
                            </h1>
                        </div> : ""
                    }

                    <div className={"section-option"}>
                        <div className={"flex "+this.state.radio_option_1} onClick={() => this.selectRadio(1)}>
                            <div className={"option"}>
                                <div className={"radio "+this.state.radio_option_1}>
                                    <div>
                                        &nbsp;
                                    </div>
                                </div>
                                <div className={"text"}>
                                    CREDIT CARD
                                </div>
                            </div>
                        </div>
                        <div className={"flex "+this.state.radio_option_2} onClick={() => this.selectRadio(2)}>
                            <div className={"option"}>
                                <div className={"radio "+this.state.radio_option_2}>
                                    <div>
                                        &nbsp;
                                    </div>
                                </div>
                                <div className={"text"}>
                                    MONEY MATRIX
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className={"line-separator"}>&nbsp;</div>
                </section>
            </div>
        )
    }
});


module.exports = EmOptionSelector;
