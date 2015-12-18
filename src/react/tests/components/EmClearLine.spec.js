var React = require('react'),
    ReactDOM = require('react-dom'),
    EmClearLine = require('../../components/EmClearLine.js'),
    EuroMillionsLineRow = require('../../components/EmLineRow.js'),
    TestUtils = require('react-addons-test-utils'),
    expect = require('chai').expect;

describe('EmClearLine', function() {

    it('when onClearclick should empty array numbers', function() {
        var numbers = [5];
        var numbers_row = [1,2,3,4,5];
        var show_btn_clear = false;

        var onClickNumber = function() {
            show_btn_clear = true;
        }

        var emLineRow = <EuroMillionsLineRow numbers={numbers_row} onNumberClick={onClickNumber}
                             selectedNumbers={numbers} key="1"/>

        var onClickBtn = function(event) {
            numbers= [];
        };

        var emClearBtn = <EuroMillionsClearLine showed={show_btn_clear} onClearClick={onClickBtn}/>
        expect(numbers.length).to.equal([]);

    });


});