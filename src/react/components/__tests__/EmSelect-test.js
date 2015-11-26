jest.dontMock('../EmSelect');

describe('EuroMillions Select', function() {
    var React = require('react');
    var ReactDOM = require('react-dom');
    var TestUtils = require('react-addons-test-utils');
    var EmSelect = require('../EmSelect');

    var options = [
        {text: '50 text', value: '50'},
        {text: '75 text', value: '75'},
        {text: '100 text', value: '100'},
        {text: 'custom text', value: 'custom'}
    ];

    it('renders provided options', function () {

       var em_select = TestUtils.renderIntoDocument(
           <EmSelect options={options} />
       );
       var actual_options = ReactDOM.findDOMNode(em_select).childNodes[1].childNodes;

       for(var i=0; i<4; i++) {
           expect(actual_options[i].text).toEqual(options[i].text);
           expect(actual_options[i].value).toEqual(options[i].value);
       }
    });

    it('div text corresponds to selected option', function() {
        var em_select = TestUtils.renderIntoDocument(
            <EmSelect options={options} defaultValue="75" defaultText="75 text" />
        );
        var select = ReactDOM.findDOMNode(em_select).childNodes[1];
        TestUtils.Simulate.change(select, {
            "target": {
                "value": '100',
                "selectedOptions": [
                    {"text": '100 text'}
                ]}
        });

        var div_text = ReactDOM.findDOMNode(em_select).childNodes[0].textContent;

        expect(div_text).toBe('100 text');
    });

    it('doesn\'t change the value nor the div text when the parent handleChange returns false', function (){
        var parentOnChange = function(event) {
            return false;
        };
        var em_select = TestUtils.renderIntoDocument(
            <EmSelect options={options} defaultValue="75" defaultText="75 text" onChange={parentOnChange}/>
        );
        var select = ReactDOM.findDOMNode(em_select).childNodes[1];
        TestUtils.Simulate.change(select, {
            "target": {
                "value": '100',
                "selectedOptions": [
                    {"text": '100 text'}
                ]}
        });

        var div_text = ReactDOM.findDOMNode(em_select).childNodes[0].textContent;

        expect(div_text).toBe('75 text');
        expect(select.value).toBe('75');
    });

});