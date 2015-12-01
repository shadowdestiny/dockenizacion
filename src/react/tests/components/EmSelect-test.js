var React = require('react'),
    EmSelect = React.createFactory(require('../../components/EmSelect.js')),
    TestUtils = require('react-addons-test-utils'),
    sinon = require('sinon'),
    expect = require('chai').expect;

describe('EmSelect', function() {
    var options = [
        {text: '50 text', value: '50'},
        {text: '75 text', value: '75'},
        {text: '100 text', value: '100'},
        {text: 'custom text', value: 'custom'}
    ];

    it ('render provided options', function () {
        let em_select = TestUtils.renderIntoDocument(
            <EmSelect options={options} />
        );
        let actual_options = ReactDOM.findDOMNode(em_select).childNodes[1].childNodes;

        for(let i=0; i<4; i++) {
            expect(actual_options[i].text).to.equal(options[i].text);
            expect(actual_options[i].value).to.equal(options[i].value);
        }

    });
});
