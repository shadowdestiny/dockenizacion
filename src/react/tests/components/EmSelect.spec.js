var React = require('react'),
    ReactDOM = require('react-dom'),
    EmSelect = require('../../components/EmSelect.js'),
    TestUtils = require('react-addons-test-utils'),
    expect = require('chai').expect;

describe('EmSelect', function() {
    var options = [
        {text: '50 text', value: '50'},
        {text: '75 text', value: '75'},
        {text: '100 text', value: '100'},
        {text: 'custom text', value: 'custom'}
    ];

    it ('render provided options', function () {
        var em_select = TestUtils.renderIntoDocument(
            <EmSelect options={options} />
        );
        var actual_options = ReactDOM.findDOMNode(em_select).childNodes[1].childNodes;
        console.log(actual_options);

        for(let i=0; i<4; i++) {
            expect(actual_options[i].text).to.equal(options[i].text);
            expect(actual_options[i].value).to.equal(options[i].value);
        }
    });

});
