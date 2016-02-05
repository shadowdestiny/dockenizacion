var React = require('react');

var EmLineOrder = new React.createClass({

    displayName: 'EmLineOrder',

    render : function ()
    {

        var numbers = this.props.numbers.split(',');
        var stars = this.props.stars.split(',');
        var char_line = 'ABCDEFGHIJKLMNOPQRSTVWXYZ'.charAt(this.props.line);
        return (
            <div className="row cl">
                <div className="desc">
                    Line {char_line}
                </div>
                <div className="detail">
                    <ol className="no-li num">
                        {
                            numbers.map(function(number,i) {
                                return <li key={i}>{number}</li>
                            })
                        }
                        {
                            stars.map(function(star,i) {
                                return <li className="yellow" key={i}>{star}</li>
                            })
                        }
                    </ol>
                </div>
                <div className="summary val">{this.props.single_bet_price}</div>
            </div>
        )
    }
});

module.exports = EmLineOrder;