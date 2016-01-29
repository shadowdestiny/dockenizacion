var React = require('react');
var EmSelect = require('./EmSelect');
var ReactTooltip = require("react-tooltip");

var EmSelectDrawDate = React.createClass({


    getInitialState: function () {
        return {
            dates : []
        }
    },

    componentDidMount: function() {
    },

    handleChange: function (event) {
        this.props.change_date(event.target.value);
    },

    render: function () {

        var disabled = !this.props.active;
        var react_tooltip = <ReactTooltip type="light" id='select-draw-date'/>;

        var select = <EmSelect
            options={this.props.options}
            defaultValue={this.props.defaultValue}
            defaultText={this.props.defaultText}
            onChange={this.handleChange}
            useTextAsValue={false}
            disabled={disabled}/>;

        return (
            <div className="col2">
                <label className="label">First Draw
                    <div data-for="select-draw-date" className="wrap" data-tip="When do you want to start to play the first draw?">
                        <svg className="ico v-question-mark"><use xlinkHref="/w/svg/icon.svg#v-question-mark"></use></svg>
                    </div>
                </label>
                {select}
                {react_tooltip}
            </div>
        )
    }

})

module.exports = EmSelectDrawDate