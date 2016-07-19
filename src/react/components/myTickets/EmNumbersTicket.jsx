var React = require('react');

var EmNumbersTicket = new React.createClass({

    propTypes: {
    },
    render : function () {
        var nums = '1 2 3 4 5 6';
        var stars = '1 2';
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

module.exports = EmNumbersTicket;