var React = require('react');
var ReactDOM = require('react-dom');
var EmSelect = require('./em_select/main.js');

var options = [
    {text: '50 millions €', value: '50'},
    {text: '75 millions €', value: '75'},
    {text: '100 millions €', value: '100'}
];

ReactDOM.render(
    <EmSelect options={options} defaultValue="75" defaultText="75 millions €"/>,
    document.getElementById('threshold_select')
);
