import React, { Component } from 'react';
import BootstrapTable from 'react-bootstrap-table-next';
import * as Comparator from "react-bootstrap-table2-filter/lib/src/comparison";
import cellEditFactory from 'react-bootstrap-table2-editor';
import filterFactory, {dateFilter} from 'react-bootstrap-table2-filter';
import paginationFactory from 'react-bootstrap-table2-paginator';
import Modal from 'react-bootstrap-modal';
import axios from "axios";
/**/


import 'react-bootstrap-table2-paginator/dist/react-bootstrap-table2-paginator.min.css?raw';
import '../../../src/styles/shared/components/pagination.scss?raw';
import bootstrap from '../../../node_modules/bootstrap/dist/css/bootstrap.css'
//import '../../../node_modules/bootstrap/dist/css/bootstrap.min.css'
import '../../../src/styles/shared/components/datatable.scss?raw'
import '../../../src/styles/shared/components/modal.scss?raw'
import 'react-bootstrap-table2-filter/dist/react-bootstrap-table2-filter.min.css';
import style from './EmDrawsComponent.scss';

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
                    typeMoney:'€',
                    breakDown : {}
                },
            ],
            draws:[
               /* {
                    id : 1,
                    match : "5 + 2",
                    prize : 0,
                    winners : 0,
                },*/

            ],

        };
    }

    mapperJackpot(rows){


        let formatDate = (date) => {
            let monthName = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return date.getDay()+" "+monthName[date.getMonth()]+" "+date.getFullYear();
        };

        let formatNumber = (results) =>{
          return  results.regular_number_one + " "
              + results.regular_number_two + " "
              + results.regular_number_three + " "
              + results.regular_number_four + " "
              + results.regular_number_five + " "
              + results.lucky_number_one + " "
              + results.lucky_number_two ;
        };

        let formatBreakDown = (break_down) =>{
            let breakDowns = [];

            breakDowns.push({id : 1,match : break_down.category_one.numbers_corrected + " + " + break_down.category_one.stars_corrected,prize : break_down.category_one.lottery_prize,winners : break_down.category_one.winners});
            breakDowns.push({id : 2,match : break_down.category_two.numbers_corrected + " + " + break_down.category_two.stars_corrected,prize : break_down.category_two.lottery_prize,winners : break_down.category_two.winners});
            breakDowns.push({id : 3,match : break_down.category_three.numbers_corrected + " + " + break_down.category_three.stars_corrected,prize : break_down.category_three.lottery_prize,winners : break_down.category_three.winners});
            breakDowns.push({id : 4,match : break_down.category_four.numbers_corrected + " + " + break_down.category_four.stars_corrected,prize : break_down.category_four.lottery_prize,winners : break_down.category_four.winners});
            breakDowns.push({id : 5,match : break_down.category_five.numbers_corrected + " + " + break_down.category_five.stars_corrected,prize : break_down.category_five.lottery_prize,winners : break_down.category_five.winners});
            breakDowns.push({id : 6,match : break_down.category_six.numbers_corrected + " + " + break_down.category_six.stars_corrected,prize : break_down.category_six.lottery_prize,winners : break_down.category_six.winners});
            breakDowns.push({id : 7,match : break_down.category_seven.numbers_corrected + " + " + break_down.category_seven.stars_corrected,prize : break_down.category_seven.lottery_prize,winners : break_down.category_seven.winners});
            breakDowns.push({id : 8,match : break_down.category_eight.numbers_corrected + " + " + break_down.category_eight.stars_corrected,prize : break_down.category_eight.lottery_prize,winners : break_down.category_eight.winners});
            breakDowns.push({id : 9,match : break_down.category_nine.numbers_corrected + " + " + break_down.category_nine.stars_corrected,prize : break_down.category_nine.lottery_prize,winners : break_down.category_nine.winners});
            breakDowns.push({id : 10,match : break_down.category_ten.numbers_corrected + " + " + break_down.category_ten.stars_corrected,prize : break_down.category_ten.lottery_prize,winners : break_down.category_ten.winners});
            breakDowns.push({id : 12,match : break_down.category_eleven.numbers_corrected + " + " + break_down.category_eleven.stars_corrected,prize : break_down.category_eleven.lottery_prize,winners : break_down.category_eleven.winners});
            breakDowns.push({id : 13,match : break_down.category_twelve.numbers_corrected + " + " + break_down.category_twelve.stars_corrected,prize : break_down.category_twelve.lottery_prize,winners : break_down.category_one.winners});
            // breakDowns.push({d : 14,match : break_down.category_thirteen.numbers_corrected + " + " + break_down.category_thirteen.stars_corrected,prize : break_down.category_thirteen.lottery_prize,winners : break_down.category_one.winners});
            return breakDowns;
        };

        let jackpots = [];
        rows.forEach((row,i)=>{
            jackpots.push({
                id          : row.id,
                date        : formatDate(new Date(row.date)),
                numbers     : formatNumber(row.results),
                typeMoney   : '€',
                jackpot     : row.jackpot,
                breakDown   : formatBreakDown(row.break_down)
            })
        });
        return jackpots;
    }

    componentWillMount() {

        axios.get('/admin/results/eurojackpot').then((response)=>{
            if(response.data.result === "Ok"){
                this.setState({
                   jackpots :  this.mapperJackpot(response.data.data)
                });
            }
        })
        .catch((error) => {
            console.log(error);
        });
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
                        formatter:(cell)=>{
                            return <strong>{ cell }</strong>
                        },
                        editorRenderer:(editorProps, value)=>{
                            return <strong>{ value}</strong>
                        },
                    },
                    {
                        dataField: 'prize',
                        text: 'Prize',
                        headerStyle:{width:"33%"},
                        editorRenderer:(editorProps, value, row, column, rowIndex, columnIndex)=>{
                            return <span className={`value ${style.value}`}> € <input type={"text"} ref={ value } defaultValue={ value } onKeyPress={(e)=>this.editRow(e,value,editorProps)} onChange={()=>{}}/></span>;
                        },
                        formatter: (cell) => {
                            return <div><span className={`value ${style.value}`}>€</span><span>{ cell }</span></div>
                        },
                        validator: (newValue, row, column) => {
                            if (isNaN(newValue)) {
                                return {
                                    valid: false,
                                    message: 'Price should be numeric'
                                };
                            }
                            let reg = /^-?\d*(\.\d+)?$/;
                            if (reg.test(reg)) {
                                return {
                                    valid: false,
                                    message: 'Price invalid'
                                };
                            }
                            return true;
                        }
                    },
                    {
                        dataField: 'winners',
                        text: 'Winners',
                        headerStyle:{width:"33%"},
                        validator: (newValue, row, column) => {
                            if (isNaN(newValue)) {
                                return {
                                    valid: false,
                                    message: 'Winner should be numeric'
                                };
                            }
                            let reg = /^\d+$/;
                            if (reg.test(reg)) {
                                return {
                                    valid: false,
                                    message: 'Value invalid'
                                };
                            }
                            return true;
                        }
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
                text: 'ID',
                hidden:true,
            },
            {
                dataField: 'date',
                text: 'Date',
                sort: true,
                filter: dateFilter(dateFilterConfig),
                headerStyle:{width:"15%"},
                formatter: (cell) => {
                    return `${cell}`;
                },
            },
            {
                dataField: 'jackpot',
                text: 'Jackpot',
                sort: true,
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
            draws : row.breakDown,
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
                            <input type="text" className={bootstrap["form-control"]} id="inputDate" placeholder="Date" value={this.state.inputEdit.date} onChange={() => {}} disabled={true}/>
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
                <div>
                    <div className={bootstrap.row}>
                        <div className={bootstrap["col-md-6"]}>
                            <a className="left btn btn-danger defaultColor" onClick={(e) => this.cancelEdit(e)}>Cancel</a>
                        </div>
                        <div className={bootstrap["col-md-6"]}>
                            <a className="right btn btn-primary defaultColor" onClick={(e) => this.cancelEdit(e)}>Save</a>
                        </div>
                    </div>
                    <br />
                    <BootstrapTable
                        keyField='id'
                        data={ this.state.draws }
                        columns={ this.drawTable() }
                        cellEdit={ cellEditFactory({ mode: 'click' }) }
                        pagination={ paginationFactory() }
                    />
                    </div>
                    <br/>
                    <div className={bootstrap.row}>
                        <div className={bootstrap["col-md-6"]}>
                            <a className="left btn btn-danger defaultColor" onClick={(e) => this.cancelEdit(e)}>Cancel</a>
                        </div>
                        <div className={bootstrap["col-md-6"]}>
                            <a className="right btn btn-primary defaultColor" onClick={(e) => this.cancelEdit(e)}>Save</a>
                        </div>
                    </div>
                    <br />
                </div>
        }
        return editSection;
    }

    render() {

        let closeModal = () => this.setState({ open: false })

        return (
            <div className="module">
                <div className="module-body">
                    <h1 className="h1 purple">Jackpot</h1>
                    <div>
                        <div className={bootstrap.row}>
                            <div className={bootstrap["col-md-6"]}>
                                <a className={"btn btn-primary search right add defaultColor "+style.left} onClick={()=>(this.setState({isSearch:!this.state.isSearch}))}>Search</a>
                            </div>
                            <div className={bootstrap["col-md-6"]}>
                                <a className="btn btn-primary right add defaultColor" onClick={()=>(this.setState({open:true}))}>Add New</a>
                            </div>
                        </div>

                        <BootstrapTable
                            striped
                            hover
                            keyField='id'
                            data={ this.state.jackpots }
                            columns={ this.searchTable() }
                            filter={ filterFactory() }
                            pagination={ paginationFactory() }
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
                                <button className='btn btn-primary defaultColor' onClick={(e)=>(this.saveAndClose(e))}>
                                    Save
                                </button>
                            </Modal.Footer>
                        </Modal>
                    </div>
                </div>
            </div>
        );
    }
}

export default EmDrawsComponent;
