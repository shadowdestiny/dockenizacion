var React = require('react');
var createReactClass = require('create-react-class');
var EuroMillionsCheckMark = createReactClass({

    getDefaultProps: function() {
        return {
            numbers_length : 0,
            stars_length : 0,
            maxNumbers : 5,
            maxStars : 2,
        }
    },

    render: function () {
        if(this.props.numbers_length == this.props.maxNumbers
                &&
                    this.props.stars_length == this.props.maxStars
        ) {
            return(
                <svg className="ico v-checkmark"><use xlinkHref="/w/svg/icon.svg#v-checkmark"></use></svg>
            );
        } else {
            return null;
        }
    }

});

export default  EuroMillionsCheckMark;
