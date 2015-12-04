var React = require('react');

var EuroMillionsCheckMark = React.createClass({

    getDefaultProps: function() {
        return {
            numbers_length : 0,
            stars_length : 0,
        }
    },

    getInitialState: function () {
        return {
            maxNumbers : 5,
            maxStars : 2,
        };
    },

    render: function () {
        if(this.props.numbers_length == this.state.maxNumbers
                &&
                    this.props.stars_length == this.state.maxStars
        ) {
            return(
                <svg dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-checkmark"></use>'}}
                     className="ico v-checkmark"/>
            );
        } else {
            return null;
        }
    }

});

module.exports = EuroMillionsCheckMark;