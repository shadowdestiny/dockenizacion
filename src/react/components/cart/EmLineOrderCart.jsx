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

        let list_number_ball = [];
        let list_ball = [];
        let _class = '';

        numbers.map(function(number,i) {
            list_number_ball.push(parseInt(number));
        });

        // only megasena
        if(this.props.megasena){
            _class = 'circle_megasena';
            list_number_ball.push(parseInt(stars[1]));
            list_ball = list_number_ball
                .sort((a, b) => a - b )
                .map(function(number,i) {
                    return <li key={i} className={_class}>{(( parseInt(number.toString()) < 10) ? ("0" + number): number)}</li>
                });
        } else {
            list_ball = list_number_ball.map(function(number,i) {
                return <li key={i} className={_class}>{number}</li>
            });
        }

        return (
            <div className={"row cl"}>
                <div className={"desc"}>
                    {this.props.txt_line} {num_char_line}
                    {this.props.megamillions && this.props.powerplay ? '('+this.props.playingMM+')' : ''}
                    {this.props.powerplay && this.props.powerball ? '('+this.props.playingPP+')' : ''}
                </div>
                <div className={'detail'}>
                    <ul className="no-li inline numbers small">
                        {
                            list_ball
                        }
                        {
                            (this.props.powerball ?  <li className="star_red">{stars[1]}</li> : "")
                        }
                        {
                            (this.props.megamillions ?  <li className="star_blue">{stars[1]}</li> : "")
                        }
                        {
                            (this.props.eurojackpot  ?
                                stars.map(function(star,i) {
                                    return <li className="ellipse_eurojackpot" key={i}>{star}</li>})
                                : ""
                            )
                        }
                        {
                            (!this.props.megamillions && !this.props.powerball && !this.props.eurojackpot && !this.props.megasena ?
                                stars.map(function(star,i) {
                                    return <li className="star" key={i}>{star}</li>})
                                : ""
                            )
                        }

                    </ul>
                </div>
                <div className="summary val">{this.props.single_bet_price}</div>
            </div>
        )
    }
});

module.exports = EmLineOrder;