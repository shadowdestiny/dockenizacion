var React = require('react');
var ReactDOM = require('react-dom');
var ReactTooltip = require("react-tooltip")


var Tooltip = new React.createClass({


    getDefaultProps : function () {
        return ( {
           type : 'span'
        });
    },


    render : function (){

        var react_tooltip = <ReactTooltip type="light" id='tooltip'/>;
        var xlink = "/w/svg/icon.svg#" +this.props.ico;

        if(this.props.type == 'span') {
            return (
                <span data-for="tooltip" data-place={this.props.place} data-tip={this.props.message}>
                    <svg className={this.props.class}><use xlinkHref={xlink}></use></svg>
                        {react_tooltip}
                </span>
            )
        }
        if(this.props.type == 'input') {
            //ETMD for the moment the style inline is hard coded. Next step pass style via props.
            var spanStyle = {
                float : 'left',
                width : '48.5%'
            };
            return (
                    <span style={spanStyle}>
                    <input data-for="tooltip" data-offset="{'top': 0, 'left': 0}" data-event={this.props.event} data-place={this.props.place} type="password" id="password" name="password" className={this.props.class} placeholder="Password" data-tip={this.props.message}/>
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

