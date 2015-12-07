var React = require('react'),
    ReactDOM = require('react-dom'),
    EmSelect = require('../../components/EmSelect.jsx'),
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
        var actual_options = TestUtils.scryRenderedDOMComponentsWithTag(em_select,'option');

        for(let i=0; i<4; i++) {
            expect(actual_options[i].text).to.equal(options[i].text);
            expect(actual_options[i].value).to.equal(options[i].value);
        }
    });

    it ('render div text corresponding to selected option', function() {
        var em_select = TestUtils.renderIntoDocument(
            <EmSelect options={options} defaultValue="75" defaultText="75 text" />
        );
        var select = TestUtils.findRenderedDOMComponentWithTag(em_select, 'select');
        TestUtils.Simulate.change(select, {
            "target": {
                "value": '100',
                "selectedOptions": [
                    {"text": '100 text'}
                ]}
        });

        var div_text = TestUtils.scryRenderedDOMComponentsWithTag(em_select,'div')[1].textContent;
        expect(div_text).to.equal('100 text');
    });

    it ('doesn\'t change the value nor the div text when the parent handleChange returns false', function () {
        var parentOnChange = function(event) {
            return false;
        };
        var em_select = TestUtils.renderIntoDocument(
            <EmSelect options={options} defaultValue="75" defaultText="75 text" onChange={parentOnChange}/>
        );
        var select = TestUtils.findRenderedDOMComponentWithTag(em_select, 'select');
        TestUtils.Simulate.change(select, {
            "target": {
                "value": '100',
                "selectedOptions": [
                    {"text": '100 text'}
                ]}
        });

        var div_text = TestUtils.scryRenderedDOMComponentsWithTag(em_select,'div')[1].textContent;

        expect(div_text).to.equal('75 text');
        expect(select.value).to.equal('75');
    });

});
