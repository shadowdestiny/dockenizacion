var React = require('react');
import ReactTooltip from "react-tooltip";
import EmSelect from './EmSelect.jsx';
var createReactClass = require('create-react-class');
var EmSelectDraw = createReactClass({

    handleChange: function (event) {
          this.props.play_days(event.target.value);
    },

    render: function () {

        var disabled = !this.props.active;
        var react_tooltip = <ReactTooltip type="light" id='select-draw'/>;

        var select = <EmSelect
            options={this.props.options}
            defaultValue={this.props.defaultValue}
            defaultText={this.props.defaultText}
            onChange={this.handleChange}
            disabled={disabled}/>;

        return (
            <div className="col2">
                <label className="label">Draw
                    <div data-for="select-draw" className="wrap" data-tip="Which draw do you want to play?">
                        <svg className="ico v-question-mark"><use xlinkHref="/w/svg/icon.svg#v-question-mark"></use></svg>
                    </div>
                </label>
                {select}
                {react_tooltip}
            </div>
        )
    }
})


export default  EmSelectDraw;