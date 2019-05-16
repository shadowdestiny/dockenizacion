import React, {Component, Fragment} from 'react';
import ReactDOM from 'react-dom';

import styles from '../../src/styles/main.scss'
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
                <div className={styles.azul}>
                    hola
                    <EmDrawsComponent></EmDrawsComponent>
                </div>
            </Fragment>

        );
    }
}

export default Draws;

ReactDOM.render(
    <Draws />
    , document.getElementById('admin-draws'));