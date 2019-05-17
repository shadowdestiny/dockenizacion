import React, { Component } from 'react';
import BootstrapTable from 'react-bootstrap-table-next';
import * as Comparator from "react-bootstrap-table2-filter/lib/src/comparison";
import cellEditFactory from 'react-bootstrap-table2-editor';
import filterFactory, {dateFilter, textFilter} from 'react-bootstrap-table2-filter';
/**/

import Modal from 'react-bootstrap-modal';
import bootstrap from '../../../node_modules/bootstrap/dist/css/bootstrap.min.css'
//import '../../../node_modules/react-bootstrap-table/dist/react-bootstrap-table-all.min.css?raw';
import '../../../src/styles/shared/components/datatable.scss?raw'
import '../../../src/styles/shared/components/modal.scss?raw'
import 'react-bootstrap-table2-filter/dist/react-bootstrap-table2-filter.min.css';
import style from './EmDrawsComponent.css';
import cn from "classnames";


/*
    https://react-bootstrap-table.github.io/react-bootstrap-table2/docs/basic-celledit.html
*/

class EmDrawsComponent extends Component {

    constructor(props, context) {
        super(props, context);

        this.state = {
            open : false,
            isSearch : false,
            isShowEditSection: false,
            inputEdit:{
              id:'',
              date:'',
              numbers:'',
              jackpot:'',
              lucky:'',
              typeMoney:'€'
            },
            inputAdd:{
                id:'',
                date:'',
                numbers:'',
                jackpot:'',
                lucky:'',
            },
            jackpots : [
                {
                    id: 1,
                    date: "10 May 2019",
                    jackpot: "17.000.000",
                    numbers: "1 4 27 45 48 1 7",
                    actions: "actions",
                    typeMoney:'€'
                },
                {
                    id: 2,
                    date: "10 May 2019",
                    jackpot: "25.000.000",
                    numbers: "10 14 17 25 50 2 10",
                    actions: "actions",
                    typeMoney:'€'
                },
            ],
            draws:[
                {
                    id : 1,
                    match : "5 + 2",
                    prize : 0,
                    winners : 0
                },
                {
                    id : 2,
                    match : "5 + 1",
                    prize : 4826977400,
                    winners : 2
                }
            ],

        };
    }

    editRow(e,value,editorProps){

        if (e.which === 13)
            editorProps.onUpdate(e.target.value);

    }

    drawTable(){
            return [{
                        dataField: 'id',
                        hidden:true,
                    },
                    {
                        dataField: 'match',
                        text: 'Match',
                        headerStyle:{width:"33%"},
                    },
                    {
                        dataField: 'prize',
                        text: 'Prize',
                        headerStyle:{width:"33%"},
                        editorRenderer:(editorProps, value, row, column, rowIndex, columnIndex)=>{
                            return <span className={`value ${style.value}`}> € <input type={"text"} ref={ value } defaultValue={ value } onKeyPress={(e)=>(this.editRow(e,value,editorProps))} onChange={(e)=>{}}/></span>;
                        },
                        formatter: (cell) => {
                            return <div><span className={`value ${style.value}`}>€</span><span>{ cell }</span></div>
                        },
                    },
                    {
                        dataField: 'winners',
                        text: 'Winners',
                        headerStyle:{width:"33%"},
                    }
                ]
    }

    searchTable(){

        const dateFilterConfig = {
            delay: 600,  // how long will trigger filtering after user typing, default is 500 ms
            placeholder: 'custom placeholder',  // placeholder for date input
            withoutEmptyComparatorOption: true,  // dont render empty option for comparator
            comparators: [Comparator.EQ],  // Custom the comparators
            style: { display: this.state.isSearch ? 'block':'none' },  // custom the style on date filter
            className: 'custom-dateFilter-class2',  // custom the class on date filter
            //comparatorStyle: { backgroundColor: 'antiquewhite' }, // custom the style on comparator select
            //comparatorClassName: 'custom-comparator-class2',  // custom the class on comparator select
            //dateStyle: { backgroundColor: 'cadetblue', margin: '0px' },  // custom the style on date input
            //dateClassName: 'custom-date-class',  // custom the class on date input
            //defaultValue: { date: new Date(2018, 0, 1), comparator: Comparator.GT }  // default value
        };

        return  [
            {
                dataField: 'id',
                text: 'Product ID',
                hidden:true,
            },
            {
                dataField: 'date',
                text: 'Date',
                sort: true,
                filter: dateFilter(dateFilterConfig),
                headerStyle:{width:"15%"},
                formatter: (cell) => {
                    /*console.log(cell);
                    let dateObj = cell;
                    if (typeof cell !== 'object') {
                        dateObj = new Date(cell);
                    }
                    if (cell == null) {
                        return
                    }*/
                    //return `${('0' + (dateObj.getMonth() + 1)).slice(-2)}/${('0' + dateObj.getDate()).slice(-2)}/${dateObj.getFullYear()}`;
                    return `${cell}`;
                },
            },
            {
                dataField: 'jackpot',
                text: 'Jackpot',
                sort: true,
                //filter: textFilter()
                headerStyle:{width:"35%"},
                formatter:(cell,row) => {
                    return `${row.typeMoney+' '+cell}`
                }
            },
            {
                dataField: 'numbers',
                text: 'Numbers',
                headerStyle:{width:"35%"},
                formatter: (cell) => {
                    let numbers = [];
                    cell.split(" ").forEach((element,i) => {
                        let lucky = '';
                        if (i > 4){
                            lucky = 'yellow';
                        }
                        numbers.push(<span key={i} className={'num '+lucky}>{ element }</span>)
                    });

                    return numbers;
                },
            },
            {
                dataField:'',
                text: 'Actions',
                headerStyle:{width:"5%"},
                formatter: (cell,row) => {
                    return <a className="btn btn-primary search right add defaultColor" onClick={() => this.clickEdit(row)}>Edit</a>
                }
            }
        ]
    }

    clickEdit = (row) => {

        let inputEdit = {id:row.id,numbers:row.numbers,lucky:row.lucky,date:row.date,jackpot:row.jackpot};

        let allNumbers  = row.numbers.split(" ");

        inputEdit.numbers   = allNumbers[0]+","+allNumbers[1]+","+allNumbers[2]+","+allNumbers[3]+","+allNumbers[4];
        inputEdit.lucky     = allNumbers[5]+","+allNumbers[6];

        this.setState({
            isShowEditSection: true,
            inputEdit
        });
    };

    cancelEdit()  {
        this.setState({
            isShowEditSection: false,
        });
    };

    saveAndClose(){
        // section save
        this.setState({
            open : false
        });
    }

    addSection(){
        return <form>
                <div className={bootstrap["form-row"]}>
                    <input type="hidden" value={this.state.inputAdd.id} onChange={() => {}}/>
                    <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                        <label htmlFor="inputDate">Date</label>
                        <input type="text" className={bootstrap["form-control"]} id="inputDate" placeholder="Date" value={this.state.inputAdd.date} onChange={() => {}}/>
                    </div>
                    <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                        <label htmlFor="inputNumbers">Numbers</label>
                        <input type="text" className={bootstrap["form-control"]} id="inputNumbers" placeholder="Numbers" value={this.state.inputAdd.numbers} onChange={() => {}}/>
                    </div>
                    <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                        <label htmlFor="inputJackpot">Jackpot</label>
                        <input type="text" className={bootstrap["form-control"]} id="inputJackpot" placeholder="Jackpot" value={this.state.inputAdd.jackpot} onChange={() => {}}/>
                    </div>
                    <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                        <label htmlFor="inputLucky">Lucky</label>
                        <input type="text" className={bootstrap["form-control"]} id="inputLucky" placeholder="Lucky" value={this.state.inputAdd.lucky} onChange={() => {}}/>
                    </div>
                </div>
        </form>
    }

    editSection(){
        let editSection = '';
        if (this.state.isShowEditSection){
            editSection = <div >
                <h2 className="sub-title purple">Edit draw</h2>
                <form>
                    <div className={bootstrap["form-row"]}>
                        <input type="hidden" value={this.state.inputEdit.id} onChange={() => {}}/>
                        <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                            <label htmlFor="inputDate">Date</label>
                            <input type="text" className={bootstrap["form-control"]} id="inputDate" placeholder="Date" value={this.state.inputEdit.date} onChange={() => {}}/>
                        </div>
                        <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                            <label htmlFor="inputNumbers">Numbers</label>
                            <input type="text" className={bootstrap["form-control"]} id="inputNumbers" placeholder="Numbers" value={this.state.inputEdit.numbers} onChange={() => {}}/>
                        </div>
                        <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                            <label htmlFor="inputJackpot">Jackpot</label>
                            <input type="text" className={bootstrap["form-control"]} id="inputJackpot" placeholder="Jackpot" value={this.state.inputEdit.jackpot} onChange={() => {}}/>
                        </div>
                        <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                            <label htmlFor="inputLucky">Lucky</label>
                            <input type="text" className={bootstrap["form-control"]} id="inputLucky" placeholder="Lucky" value={this.state.inputEdit.lucky} onChange={() => {}}/>
                        </div>
                    </div>
                </form>

                <BootstrapTable
                    keyField='id'
                    data={ this.state.draws }
                    columns={ this.drawTable() }
                    cellEdit={ cellEditFactory({ mode: 'click' }) }
                />
                <br/>
                <div className={bootstrap.row}>
                    <div className={bootstrap["col-md-12"]}>
                        <a className="left btn btn-danger defaultColor" onClick={(e) => this.cancelEdit(e)}>Cancel</a>
                    </div>
                </div>
                <br />
            </div>
        }
        return editSection;
    }

    componentWillMount() {

    }

    render() {

        let closeModal = () => this.setState({ open: false })

        return (
            <div>
                <div className={bootstrap.row}>
                    <div className={bootstrap["col-md-6"]}>
                        <a className={"btn btn-primary search right add defaultColor "+style.left} onClick={(e)=>(this.setState({isSearch:!this.state.isSearch}))}>Search</a>
                    </div>
                    <div className={bootstrap["col-md-6"]}>
                        <a className="btn btn-primary right add defaultColor" onClick={(e)=>(this.setState({open:true}))}>Add New</a>
                    </div>
                </div>

                <BootstrapTable
                    striped
                    hover
                    keyField='id'
                    data={ this.state.jackpots }
                    columns={ this.searchTable() }
                    filter={ filterFactory() }
                />

                <br />

                { this.editSection() }

                <Modal
                    show={this.state.open}
                    onHide={closeModal}
                    aria-labelledby="ModalHeader"
                >
                    <Modal.Header closeButton>
                        <Modal.Title id='ModalHeader'>Add New</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        { this.addSection() }
                    </Modal.Body>
                    <Modal.Footer>
                        {/*// If you don't have anything fancy to do you can use
                        // the convenient `Dismiss` component, it will
                        // trigger `onHide` when clicked*/}
                        <Modal.Dismiss className='btn btn-default'>Cancel</Modal.Dismiss>

                        {/*// Or you can create your own dismiss buttons*/}
                        <button className='btn btn-primary' onClick={(e)=>(this.saveAndClose(e))}>
                            Save
                        </button>
                    </Modal.Footer>
                </Modal>
            </div>
        );
    }
}

export default EmDrawsComponent;
