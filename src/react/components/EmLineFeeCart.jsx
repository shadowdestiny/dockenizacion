var React = require('react');

var EmLineFeeCart = new React.createClass({


    render : function ()
    {

        return (
            <div className="row cl">
                <div className="txt-fee">
                    Fee for transactions below &euro; 12,00
                </div>
                <div className="right tweak">
                    <div className="summary val">&euro; 0,35</div>
                        <div className="box-funds cl">
                            <a className="add-funds" href="javascript:void(0)">Add Funds to avoid charges</a><br></br>
                            <div className="box-combo">
                            </div>
                        </div>
                    </div>
            </div>
        )
    }
});

module.exports = EmLineFeeCart;