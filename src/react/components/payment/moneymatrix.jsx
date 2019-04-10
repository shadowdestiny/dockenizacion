import React, {Component} from 'react';
import ReactDOM from 'react-dom';

class MoneyMatrix extends Component {

    constructor(props) {
        super(props);
        this.state = {
            typePayment : 1,
        }
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
