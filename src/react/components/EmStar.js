var React = require('react');

var EuroMillionsStar = React.createClass({

    getDefaultProps: function()
    {
        return {
            selected: false
        }
    },
    propTypes: {
        number: React.PropTypes.number.isRequired,
        selected: React.PropTypes.bool,
        onStarClick: React.PropTypes.func.isRequired
    },

    render: function () {
        var number = this.props.number;
        var class_name = this.props.selected ? 'ico s' + number + ' active' : 'ico s' + number;
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