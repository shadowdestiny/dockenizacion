var React = require('react');
var EuroMillionsAddLinesBtn = require('./EmAddLinesBtn.jsx');

var EuroMilliosnBoxActionPlay = React.createClass({

    render : function() {
        var elem = [];
        return (
            <ul class="no-li cl box-action">
                elem.push(<EuroMillionsAddLinesBtn />);
            </ul>
        );
    }
})

module.exports = EuroMilliosnBoxActionPlay;