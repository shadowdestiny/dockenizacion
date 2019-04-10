import React, {Component} from 'react';
import ReactDOM from 'react-dom';

class MoneyMatrix extends Component {

    constructor(props) {
        super(props);
    }

    componentWillMount() {

    }

    render() {

        return (
            <div className='section--money-matrix'>
                <iframe src={this.props.moneymatrix_url} ></iframe>
            </div>
        );
    }
}

export default MoneyMatrix;
