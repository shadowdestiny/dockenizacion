var React = require('react');
var  PropTypes = require ('prop-types');
var createReactClass = require('create-react-class');
var EmNumbersRows = createReactClass({

    propTypes: {
        length: PropTypes.number,
    },

    render : function () {
        return (
            <td>
                <div className="myCol">
                    <span className="num">{{nums}}</span>
                    <span className="num star">{{stars}}</span>
                </div>
            </td>
        )
    }

});

export default  EmNumbersRows;