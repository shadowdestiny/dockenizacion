var React = require('react');
var ThresholdPlay = require('../components/EmThresholdPlay.jsx');
var EmDrawConfig = require('../components/EmDrawConfig.jsx');

var EmConfigPlayBlock = new React.createClass({

    displayName : 'ConfigPlayBlock',


    getInitialState: function () {
        return {
            show_block: this.props.show,
            show_config : true
        }
    },

    getDefaultProps: function() {
        return {
            date_play : 0,
            duration : 1,
            play_days : 1
        }
    },

    componentWillReceiveProps : function(nextProps) {
        if(nextProps.reset_config) {
            this.setState({show_config: true});
        } else {
            this.setState({show_config: nextProps.show_config});
        }
    },

    handleThreshold : function(value)
    {
        this.props.update_threshold(value);
    },

    handleClickClose : function() {

    },

    render : function () {

        var default_value = '75';
        var default_text = '75 millions €';
        var custom_value = 'custom';

        var options = [
            {text: '50 millions €', value: '50'},
            {text: default_text, value: default_value},
            {text: '100 millions €', value: '100'},
            {text: 'Choose threshold', value: custom_value}
        ];

        if(!this.props.show) {
            return null;
        } else {
            return (
                <div className="advanced-play">
                    <hr className="hr yellow" />
                    <a href="javascript:void(0);" onClick={this.props.reset} className="close"><svg className="ico v-cancel-circle"><use xlinkHref="/w/svg/icon.svg#v-cancel-circle"></use></svg>
                    </a>
                    <div className="cols">
                        <EmDrawConfig active={this.state.show_config} draw_days_selected={this.props.draw_days_selected} current_duration_value={this.props.current_duration_value} draw_dates={this.props.draw_dates} date_play={this.props.date_play} draw_duration={this.props.draw_duration} duration={this.props.duration} play_days={this.props.play_days}  customValue={custom_value}/>
                        <ThresholdPlay active={this.state.show_config} callback_threshold={this.handleThreshold} options={options} customValue={custom_value} defaultValue={default_value} defaultText={default_text}/>
                    </div>
                </div>
            )
        }
    }
});

module.exports = EmConfigPlayBlock;