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

    handleVisibility : function(value)
    {
        this.setState( {
            show_config : value ? false : true
        })
    },

    componentWillReceiveProps: function (nextProps) {

        if(!nextProps.show && !this.state.show_block) {
            this.state.show_block = true;
        }else if(nextProps.show && this.state.show_block) {
            this.state.show_block = false;
        } else {
            this.state.show_block = nextProps.show;
        }
    },

    handleClickClose : function() {
        var isShowed = this.state.show_block ? false : true;
        this.setState({ show_block : isShowed });
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
        var options_draw_days = [
            {text: 'Tuesday & Friday' , value : '2,5'},
            {text: 'Tuesday', value : '2'},
            {text: 'Friday' , value : '5'}
        ];

        if(!this.state.show_block) {
            return null;
        } else {
            return (
                <div className="advanced-play">
                    <hr className="hr yellow" />
                    <a href="javascript:void(0);" onClick={this.handleClickClose} className="close"><svg className="ico v-cancel-circle"><use xlinkHref="/w/svg/icon.svg#v-cancel-circle"></use></svg>
                    </a>
                    <div className="cols">
                        <EmDrawConfig show_config={this.state.show_config} draw_days_selected={this.props.draw_days_selected} current_duration_value={this.props.current_duration_value} draw_dates={this.props.draw_dates} date_play={this.props.date_play} draw_duration={this.props.draw_duration} duration={this.props.duration} play_days={this.props.play_days}  options={options_draw_days} customValue={custom_value}/>
                        <ThresholdPlay callback_threshold={this.handleVisibility} options={options} customValue={custom_value} defaultValue={default_value} defaultText={default_text}/>
                    </div>
                </div>
            )
        }
    }
});

module.exports = EmConfigPlayBlock;