var React = require('react');

var EmNumbersRows = new React.createClass({

    propTypes: {
        length: React.PropTypes.number,
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

module.exports = EmNumbersRows;