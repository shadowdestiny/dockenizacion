var React = require('react');
var EmCustomizableSelect = require('../components/EmCustomizableSelect.js');

var PlayPage = React.createClass({
    handleCheck: function (event) {
        var active;
        if(event.target.checked) {
            active = true;
        } else {
            active = false;
        }
        this.setState({
            thresholdActive: active
        });
    },

    getInitialState: function () {
        return ({
            thresholdActive: false,
        });
    },

    render: function() {
        return (
            <div>
                <label className="label" htmlFor="threshold">Jackpot Threshold <div data-tip="Set the condition when you want to play or to be informed automatically. Thresholds are calculated only in Euro." className="wrap tipr-normal"><svg className="ico v-question-mark" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-question-mark"</use>'}} /></div></label>
                <div className="box-threshold cl">
                    <input type="checkbox" className="checkbox" id="threshold" onChange={this.handleCheck}/>
                    <EmCustomizableSelect {...this.props} active={this.state.thresholdActive}/>
                </div>
                <div class="test">Test
                </div>
            </div>
        );
    }
});


module.exports = PlayPage;