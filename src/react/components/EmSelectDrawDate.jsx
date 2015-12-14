var React = require('react');
var EmSelect = require('./EmSelect');

var EmSelectDrawDate = React.createClass({


    getInitialState: function () {
        return {
            dates : []
        }
    },

    componentDidMount: function() {
    },

    handleChange: function (event) {

    },

    render: function () {

        var disabled = !this.props.active;

        var select = <EmSelect
            options={this.props.options}
            defaultValue={this.props.defaultValue}
            defaultText={this.props.defaultText}
            onChange={this.handleChange}
            disabled={disabled}/>;

        return (
            <div className="col2">
                <label className="label">First Draw
                    <div className="wrap tipr-small" data-tip="First draw">
                        <svg className="ico v-question-mark"
                             dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-question-mark"></use>'}}/>
                    </div>
                </label>
                {select}
            </div>
        )
    }

})

module.exports = EmSelectDrawDate