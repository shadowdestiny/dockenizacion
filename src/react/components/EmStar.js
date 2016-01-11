var React = require('react');

var EuroMillionsStar = React.createClass({

    getDefaultProps: function()
    {
        return {
            selected: false,
            timeout_star_selected : 100,
            timeout_star_not_selected : 200,
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
            var delay = Math.random() * this.props.timeout_star_not_selected;
            if(nextProps.selected) {
                window.setTimeout(() => {
                    this.setState({ active : true })
                }, delay + Math.random() + this.props.timeout_star_selected);
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
    },

    propTypes: {
        number: React.PropTypes.number.isRequired,
        selected: React.PropTypes.bool,
        onStarClick: React.PropTypes.func.isRequired
    },

    render: function () {

        var number = this.props.number;
        var class_name = '';
        if(this.state.active) {
            class_name = 'ico s' + number + ' active';
        } else {
            class_name = 'ico s' + number;
        }

        if(!this.props.random_animation && !this.state.active) {
            class_name = this.props.selected ? 'ico s' + number + ' active' : 'ico s' + number;
        }

        return (
            <li className={this.props.columnClass}><a href="javascript:void(0);" onClick={this.props.onStarClick.bind(null, this.props.number)} className={class_name}>
                <svg className="vector v-star-out"
                     dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}}/>
                <svg className="vector v-star"
                     dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}}/>
                <span className="txt">{number}</span></a></li>
        );
    }
});

module.exports = EuroMillionsStar;