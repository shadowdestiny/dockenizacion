import React, {Component, Fragment} from 'react';
import ReactDOM from 'react-dom';

import '../../src/styles/shared/main.scss?raw'
/**/

import EmDrawsComponent from '../../components/admin/draws/EmDrawsComponent'

class Draws extends Component {

    constructor(props, context) {
        super(props, context);
        this.state = {
            ChristmasLottery : '',
            data : {}
        };
    }

    componentWillMount() {

    }

    render() {

        return (
            <Fragment>
                    <EmDrawsComponent></EmDrawsComponent>
            </Fragment>

        );
    }
}

export default Draws;

ReactDOM.render(
    <Draws />
    , document.getElementById('admin-draws'));