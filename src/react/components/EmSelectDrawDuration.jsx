var React = require('react');

var EmSelect = require('./EmSelect.jsx');

var EmSelectDrawDuration = React.createClass({


    handleChange: function (event) {
        this.props.change_duration(event.target.value);
    },

    render: function () {

        var disabled = !this.props.active;

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
                    <div className="wrap tipr-small" data-tip="For how long do you wish to play?">
                        <svg className="ico v-question-mark"
                             dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-question-mark"></use>'}}/>
                    </div>
                </label>
                {select}
            </div>
        )
    }
});

module.exports = EmSelectDrawDuration;