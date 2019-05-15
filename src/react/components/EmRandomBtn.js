var React = require('react');
var  PropTypes = require ('prop-types');
var createReactClass = require('create-react-class');
var EuroMillionsRandomBtn = createReactClass({

    propTypes: {
        line: PropTypes.number.isRequired,
        onBtnRandomClick: PropTypes.func.isRequired
    },

    render : function() {
        return (
            <div className="col6 not random">
                <a className="btn gwy multiplay" onClick={this.props.onBtnRandomClick.bind(null, this.props.line)} href="javascript:void(0);">
                    <svg className="v-shuffle"><use xlinkHref="/w/svg/icon.svg#v-shuffle"></use></svg>
                </a>
            </div>
        );
    }

});

export default  EuroMillionsRandomBtn;