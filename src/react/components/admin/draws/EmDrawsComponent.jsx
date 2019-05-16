import React, { Component } from 'react';
import {BootstrapTable, TableHeaderColumn} from 'react-bootstrap-table';
//import '../../../node_modules/bootstrap/scss/bootstrap.scss?raw'
import '../../../node_modules/react-bootstrap-table/dist/react-bootstrap-table-all.min.css?raw';
/**/

class EmDrawsComponent extends Component {

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

        var products = [{
            id: 1,
            name: "Product1",
            price: 120
        }, {
            id: 2,
            name: "Product2",
            price: 80
        }];

        return (
            <div className='azul'>
                hola mundo react componente
                <BootstrapTable data={products} striped hover>
                    <TableHeaderColumn isKey dataField='id'>Product ID</TableHeaderColumn>
                    <TableHeaderColumn dataField='name'>Product Name</TableHeaderColumn>
                    <TableHeaderColumn dataField='price'>Product Price</TableHeaderColumn>
                </BootstrapTable>
            </div>
        );
    }
}

export default EmDrawsComponent;
