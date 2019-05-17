var React = require('react');
var ReactDOM = require('react-dom');
import EmNumbersTicket from './myTickets/EmNumbersTicket.jsx';
var  PropTypes = require ('prop-types');
var createReactClass = require('create-react-class');
var NumbersPlayed = createReactClass({

    propTypes: {
        numbers: PropTypes.string,
        stars: PropTypes.string,
    },

    getDefaultProps : function ()
    {
        return {
            classBalls : '',
            classStars : '',
            classPrize : '',
            columns: 2,
            rows: 3,
            highLightMatchClass : ''
        };
    },


    render: function () {
        var length_row = 10 / this.props.columns;
        var total_rows = [];
        for( var i = 0; i < length_row; i++ ) {
            var rows_line = [];
            for (var j = 0; j <= this.props.columns; j++ ) {
                rows_line.push(<tr> <EmNumbersTicket numbers={this.props.numbers} stars={this.props.stars}/> </tr>);
            }
            total_rows.push(rows_line);
        }
        return (
            <table className="present">
                {{total_rows}}
            </table>
        )
    }

});

export default  NumbersPlayed;
ReactDOM.render(<NumbersPlayed numbers="" stars=""/>);



