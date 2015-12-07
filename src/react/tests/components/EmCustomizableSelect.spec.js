import {
    React,
    ReactDOM,
    TestUtils,
    chai,
    expect
} from '../testhelper';

import EmCustomizableSelect from '../../components/EmCustomizableSelect.jsx';
import EmSelect from '../../components/EmSelect.jsx';

describe('EmCustomizableSelect', function () {
    it('renders an EmSelect element on startup', function () {
        var options = [
            {text: '50 text', value: '50'},
            {text: '75 text', value: '75'},
            {text: '100 text', value: '100'},
            {text: 'custom text', value: 'custom'}
        ];
        var em_customizable_select = TestUtils.renderIntoDocument(<EmCustomizableSelect options={options} />);
        var select = TestUtils.findRenderedComponentWithType(em_customizable_select, EmSelect);
        expect(select).to.not.equal(null);
    });
});
