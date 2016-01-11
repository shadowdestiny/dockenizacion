var React = require('react');
var ReactTooltip = require("react-tooltip")
var EmSelect = require('./EmSelect.jsx');

var EmSelectDraw = React.createClass({

    handleChange: function (event) {
          this.props.play_days(event.target.value);
    },

    render: function () {

        var disabled = !this.props.active;
        var react_tooltip = <ReactTooltip type="light" id='add-lines'/>;

        var select = <EmSelect
            options={this.props.options}
            defaultValue={this.props.defaultValue}
            defaultText={this.props.defaultText}
            onChange={this.handleChange}
            disabled={disabled}/>;

        return (
            <div className="col2">
                <label className="label">Draw
                    <div className="wrap tipr-small" data-tip="Which draw do you want to play?">
                        <svg className="ico v-question-mark"
                        dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-question-mark"></use>'}}/>
                    </div>
                </label>
                {select}
            </div>
        )
    }
})


module.exports = EmSelectDraw;