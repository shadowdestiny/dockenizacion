var React = require('react');

var EuroMillionsStar = React.createClass({

    getDefaultProps: function()
    {
        return {
            selected: false,
            timeout_star_selected : 300,
            timeout_star_not_selected : 1000
        }
    },

    componentDidUpdate : function ( prevProps, prevState)
    {
        if( prevState.active ) {
            this.state.active = false;
        }
    },

    getInitialState : function()
    {
        return {
            state : false
        }
    },

    componentWillReceiveProps : function (nextProps) {
        if(nextProps.random_animation) {
            var delay_to_appear_non_selected = Math.random() * this.props.timeout_star_not_selected;
            var increase_to_appear_selected = Math.random() * this.props.timeout_star_selected;
            var delay_to_appear_selected = this.props.timeout_star_not_selected + increase_to_appear_selected;
            var delay_to_disappear = delay_to_appear_non_selected + Math.random() * this.props.timeout_star_selected;
            if(nextProps.selected) {
                window.setTimeout(() => {
                    this.setState({ active : true })
                }, delay_to_appear_selected);
            } else {
                window.setTimeout(() => {
                    this.setState({
                        active: true
                    });
                }, delay_to_appear_non_selected);
                window.setTimeout(() => {
                    this.setState({
                        active: false
                    });
                }, delay_to_disappear);
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
        onStarClick: React.PropTypes.func.isRequired
    },

    render: function () {

        var number = this.props.number;
        var class_name = '';
        if(this.state.active ){
            class_name = 'ico s' + number + ' active';
        } else {
            class_name = 'ico s' + number;
        }

        if(!this.props.random_animation && !this.state.active){
            class_name = this.props.selected ? 'ico s' + number + ' active' : 'ico s' + number;
        }

        return (
            <li className={this.props.columnClass}>
                <a href="javascript:void(0);" onClick={this.props.onStarClick.bind(null, this.props.number)} className={class_name}>
                    <svg className="vector v-star-out"><use xlinkHref="/w/svg/icon.svg#v-star-out"></use></svg>
                    <svg className="vector v-star"><use xlinkHref="/w/svg/icon.svg#v-star"></use></svg>
                    <span className="txt">{number}</span>
                </a>
            </li>
        );
    }
});

module.exports = EuroMillionsStar;