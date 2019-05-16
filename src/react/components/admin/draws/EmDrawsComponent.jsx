import React, { Component } from 'react';
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

        return (
            <div className='azul'>
                hola mundo react componente
            </div>
        );
    }
}

export default EmDrawsComponent;
