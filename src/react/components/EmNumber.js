var React = require('react');
var  PropTypes = require ('prop-types');
var createReactClass = require('create-react-class');
var EuroMillionsNumber = createReactClass({
    time_delay_to_appear_selected:[],
    time_delay_to_disappear:[],
    getDefaultProps: function()
    {
        return {
            selected: false,
            timeout_number_selected : 300,
            timeout_number_not_selected : 1000
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
            var delay_to_appear_non_selected = Math.random() * this.props.timeout_number_not_selected;
            var increase_to_appear_selected = Math.random() * this.props.timeout_number_selected;
            var delay_to_appear_selected = this.props.timeout_number_not_selected + increase_to_appear_selected;
            var delay_to_disappear = delay_to_appear_non_selected + Math.random() * this.props.timeout_number_selected;
            let number = this.props.number;
            if(nextProps.selected) {
                if (this.time_delay_to_appear_selected[number])
                    clearTimeout(this.time_delay_to_appear_selected[number]);

                this.time_delay_to_appear_selected[number] = setTimeout(() => {
                    this.setState({ active : true })
                }, delay_to_appear_selected);
            } else {
                if (this.time_delay_to_appear_selected[number])
                    clearTimeout(this.time_delay_to_appear_selected[number]);

                this.time_delay_to_appear_selected[number] = setTimeout(() => {
                    this.setState({
                        active: true
                    });
                }, delay_to_appear_non_selected);

                if (this.time_delay_to_disappear[number])
                    clearTimeout(this.time_delay_to_disappear[number]);

                this.time_delay_to_disappear[number] = setTimeout(() => {
                    this.setState({
                        active: false
                    });
                }, delay_to_disappear);
            }
        } else {
            this.setState({
                active : false
            });
        }
    },

    propTypes: {
        number: PropTypes.number.isRequired,
        selected: PropTypes.bool,
        onNumberClick: PropTypes.func.isRequired
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

export default  EuroMillionsNumber;