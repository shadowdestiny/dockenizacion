var React = require('react');

var EuroMillionsNumber = React.createClass({

    getDefaultProps: function()
    {
        return {
            selected: false,
            timeout_number_selected : 130,
            timeout_number_not_selected : 600,
        }
    },

    componentDidUpdate : function ( prevProps, prevState)
    {
        if( prevState.active ) {
            this.state.active = false;
        }
    },

    getInitialState : function ()
    {
        return {
            active :false
        }
    },

    componentWillReceiveProps : function (nextProps) {
        if(nextProps.random_animation) {
            var delay = Math.random() * this.props.timeout_number_not_selected;
            if(nextProps.selected) {
                window.setTimeout(() => {
                    this.setState({ active : true })
                }, delay + Math.random() + this.props.timeout_number_selected);
            } else {
                window.setTimeout(() => {
                    this.setState({
                        active: true
                    });
                }, delay);
                window.setTimeout(() => {
                    this.setState({
                        active: false
                    });
                }, delay + Math.random() + 40);
            }
        }
        if(!nextProps.random_animation) {
            this.setState({
                active : false
            });
        }
    },

    propTypes: {
        number: React.PropTypes.number.isRequired,
        selected: React.PropTypes.bool,
        onNumberClick: React.PropTypes.func.isRequired
    },
    render: function () {
        var class_name = (this.state.active) ? "btn gwp n" + this.props.number + " active" : "btn gwp n" + this.props.number;

        if(!this.props.random_animation && !this.state.active) {
            class_name = this.props.selected ? "btn gwp n" + this.props.number + " active" : "btn gwp n" + this.props.number;
        }

        var button = <a className={class_name} onClick={this.props.onNumberClick.bind(null, this.props.number)} href="javascript:void(0);">{this.props.number}</a>;
        return (<li className="col20per not">{button}</li>);
    }
});

module.exports = EuroMillionsNumber;