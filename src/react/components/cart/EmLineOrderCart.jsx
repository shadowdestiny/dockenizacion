var React = require('react');

var EmLineOrder = new React.createClass({

    displayName: 'EmLineOrder',

    render : function ()
    {

        var linenumber = this.props.line;
        var numbers = this.props.numbers.split(',');
        var stars = this.props.stars.split(',');

        var alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var num_char_line = '';
        for(var c = 0; c < alphabet.length; c++) {
            num_char_line = alphabet.charAt(linenumber);
            if( !num_char_line ) {
                var cur_pos = (linenumber - alphabet.length);
                var new_pos = (linenumber - alphabet.length) + 2;
                num_char_line = alphabet.charAt(cur_pos) +""+ alphabet.charAt(new_pos);
            }
        }

        return (
            <div className="row cl">
                <div className="desc">
                    Line {num_char_line}
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