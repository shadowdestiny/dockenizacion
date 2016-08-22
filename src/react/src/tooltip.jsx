var React = require('react');
var ReactDOM = require('react-dom');
var ReactTooltip = require("react-tooltip");


var Tooltip = new React.createClass({


    getDefaultProps : function () {
        return ( {
           type : 'span'
        });
    },

    render : function (){
        var react_tooltip = <ReactTooltip effect="solid" type="light" id='tooltip'/>;
        var xlink = "/w/svg/icon.svg#" +this.props.ico;

        if(this.props.type == 'span') {
            return (
                <span data-for="tooltip"  data-place={this.props.place} data-tip={this.props.message}>
                    <svg className={this.props.class}><use xlinkHref={xlink}></use></svg>
                        {react_tooltip}
                </span>
            )
        }
    }

});

module.exports = Tooltip;

var element = document.getElementsByClassName('tooltip');
for(var i=0; i < element.length ; i++ ) {
    ReactDOM.render(
        <Tooltip key={i} message={element[i].dataset.message} event={element[i].dataset.event} type={element[i].dataset.type} place={element[i].dataset.place} class={element[i].dataset.class} ico={element[i].dataset.ico} />,
        element[i]
    );
}

