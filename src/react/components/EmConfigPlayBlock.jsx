var React = require('react');
var ThresholdPlay = require('../components/EmThresholdPlay.jsx');
var EmDrawConfig = require('../components/EmDrawConfig.jsx');

var EmConfigPlayBlock = new React.createClass({

    getDefaultProps: function() {
        return {
            date_play : 0,
            duration : 1,
            play_days : 1
        }
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

        if(!this.props.show) {
            return null;
        } else {
            return (
                <div className="advanced-play">
                    <hr className="hr yellow" />
                    <a href="javascript:void(0);" className="close"><svg className="ico v-cancel-circle" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-cancel-circle"></use>'}}/>
                    </a>
                    <div className="cols">
                        <EmDrawConfig  draw_dates={this.props.draw_dates} date_play={this.props.date_play} draw_duration={this.props.draw_duration} duration={this.props.duration} play_days={this.props.play_days}  options={options_draw_days} customValue={custom_value}/>
                        <ThresholdPlay  options={options} customValue={custom_value} defaultValue={default_value} defaultText={default_text}/>
                    </div>
                </div>
            )
        }
    }
});

module.exports = EmConfigPlayBlock;