var React = require('react');

import ReactTooltip from "react-tooltip";
import EmSelect from './EmSelect.jsx';
var createReactClass = require('create-react-class');
var EmSelectDrawDuration = createReactClass({


    handleChange: function (event) {
        this.props.change_duration(event.target.value);
    },

    render: function () {

        var disabled = !this.props.active;
        var react_tooltip = <ReactTooltip type="light" id='select-draw-duration'/>;

        var select = <EmSelect
            options={this.props.options}
            defaultValue={this.props.defaultValue}
            defaultText={this.props.defaultText}
            onChange={this.handleChange}
            useTextAsValue={false}
            disabled={disabled}/>;


        return (
            <div className="col2">
                <label className="label">Duration
                    <div data-for="select-draw-duration" className="wrap" data-tip="For how long do you wish to play?">
                        <svg className="ico v-question-mark"><use xlinkHref="/w/svg/icon.svg#v-question-mark"></use></svg>
                    </div>
                </label>
                {select}
                {react_tooltip}
            </div>
        )
    }
});

// module.exports = EmSelectDrawDuration;
export default EmSelectDrawDuration;