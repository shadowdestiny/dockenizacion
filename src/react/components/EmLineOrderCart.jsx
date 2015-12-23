var React = require('react');

var EmLineOrder = new React.createClass({


    render : function ()
    {



        return (
            <div className="row cl">
                <div className="desc">
                    Line A
                </div>
                <div className="detail">
                    <ol className="no-li num">
                        <li>04</li>
                        <li>14</li>
                        <li>21</li>
                        <li>36</li>
                        <li>38</li>
                        <li className="yellow">07</li>
                        <li className="yellow">10</li>
                    </ol>
                </div>
                <div className="summary val">&euro; 20,00</div>
            </div>
        )
    }


});

module.exports = EmLineOrder;