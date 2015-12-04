var React = require('react');
var EuroMillionsAddLinesBtn = require('./EmAddLinesBtn.jsx');

var EuroMilliosnBoxActionPlay = React.createClass({

    render : function() {
        var elem = [];
        elem.push(<EuroMillionsAddLinesBtn />);
        return (
            <ul className="no-li cl box-action">
                {elem}
            </ul>
        );
    }
})

module.exports = EuroMilliosnBoxActionPlay;