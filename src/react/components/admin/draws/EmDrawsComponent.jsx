import React, { Component } from 'react';

import BootstrapTable from 'react-bootstrap-table-next';
import * as Comparator from "react-bootstrap-table2-filter/lib/src/comparison";
import cellEditFactory from 'react-bootstrap-table2-editor';
import filterFactory, {dateFilter} from 'react-bootstrap-table2-filter';
import paginationFactory from 'react-bootstrap-table2-paginator';
import Modal from 'react-bootstrap-modal';
import axios from "axios";
import objectToFormData from 'object-to-formdata';


import DayPickerInput from 'react-day-picker/DayPickerInput';
import MomentLocaleUtils, {
    formatDate,
    parseDate,
} from 'react-day-picker/moment';
import 'moment/locale/it';

import 'react-bootstrap-table2-paginator/dist/react-bootstrap-table2-paginator.min.css?raw';
import '../../../src/styles/shared/components/pagination.scss?raw';
import bootstrap from '../../../node_modules/bootstrap/dist/css/bootstrap.min.css'
import '../../../src/styles/shared/components/datatable.scss?raw'
import '../../../src/styles/shared/components/modal.scss?raw'
import 'react-day-picker/lib/style.css?raw';
import 'react-bootstrap-table2-filter/dist/react-bootstrap-table2-filter.min.css';
import style from './EmDrawsComponent.scss';


/*
    https://react-bootstrap-table.github.io/react-bootstrap-table2/docs/basic-celledit.html
*/

class EmDrawsComponent extends Component {

    constructor(props, context) {
        super(props, context);

        this.textInput = React.createRef();

        this.state = {
            open : false,
            isDone : false,
            isDoneAdd : false,
            isErrorAdd: false,
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
                draws:[
                    {
                        id : 1,
                        match : "5 + 2",
                        prize : 0,
                        winners : 0,
                    },

                ],
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
                {
                    id : 1,
                    match : "5 + 2",
                    prize : 0,
                    winners : 0,
                },

            ],
            validators:{
                numbers:{
                    text:"The number format is not correct",
                    show:false,
                },
                luckys:{
                    text:"The lucky format is not correct",
                    show:false,
                },
                jackpot:{
                    text:"The jackpot format is not correct",
                    show:false,
                },
            }

        };
    }

    mapperJackpot(rows){


        let formatDate = (dateString) => {
            let date = new Date(dateString);
            let monthName = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return (date.getDate() + 1)+" "+monthName[date.getMonth()]+" "+date.getFullYear();
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
            if (break_down.category_thirteen !== null)
                breakDowns.push({id : 14,match : break_down.category_thirteen.numbers_corrected + " + " + break_down.category_thirteen.stars_corrected,prize : break_down.category_thirteen.lottery_prize,winners : break_down.category_one.winners});
            return breakDowns;
        };

        let jackpots = [];
        rows.forEach((row,i)=>{
            jackpots.push({
                id          : row.id,
                date        : formatDate(row.date),
                numbers     : formatNumber(row.results),
                typeMoney   : '€',
                jackpot     : row.jackpot,
                breakDown   : formatBreakDown(row.break_down)
            })
        });
        return jackpots;
    }

    mapperJackpotSave(input, type = "edit"){
        let numbers = input.numbers.split(",");
        let luckys = input.lucky.split(",");
        let jackpot = input.jackpot;
        let draws;

        if (type === "edit"){
            draws = this.state.draws;
        } else {
            draws = this.state.inputAdd.draws;
        }


        let params = {
            date : this.getDateFormatToString(input.date),
            regular_number_one:numbers[0],
            regular_number_two:numbers[1],
            regular_number_three:numbers[2],
            regular_number_four:numbers[3],
            regular_number_five:numbers[4],

            lucky_number_one:luckys[0],
            lucky_number_two:luckys[1],

            jackpot:jackpot,

            prize_category_one:draws[0].prize, win_category_one: draws[0].winners,
            prize_category_two:draws[1].prize, win_category_two: draws[1].winners,
            prize_category_three:draws[2].prize, win_category_three: draws[2].winners,
            prize_category_four:draws[3].prize, win_category_four: draws[3].winners,
            prize_category_five:draws[4].prize, win_category_five: draws[4].winners,
            prize_category_six:draws[5].prize, win_category_six: draws[5].winners,
            prize_category_seven:draws[6].prize, win_category_seven: draws[6].winners,
            prize_category_eight:draws[7].prize, win_category_eight: draws[7].winners,
            prize_category_nine:draws[8].prize, win_category_nine: draws[8].winners,
            prize_category_ten:draws[9].prize, win_category_ten: draws[9].winners,
            prize_category_eleven:draws[10].prize, win_category_eleven: draws[10].winners,
            prize_category_twelve:draws[11].prize, win_category_twelve: draws[11].winners,
            prize_category_thirteen:draws[12] !== undefined ? draws[12].prize : 0, win_category_thirteen: draws[12] !== undefined ? draws[12].winners : 0,
        };

        return params;
    }

    getDateFormatToString(date){
        let year    = date.getFullYear();
        let month   =("0" + (date.getMonth() + 1)).slice(-2);
        let day     = ("0" + date.getDate()).slice(-2);
        return year+"-"+month+"-"+day;
    }

    validator(input){

        let isValid = true;
        let validators = this.state.validators;

        // numbers validation
        let reg = /^[0-9]{1,2}([,.][0-9]{1,2})([,.][0-9]{1,2}([,.][0-9]{1,2})([,.][0-9]{1,2}))?$/g;

        if (!reg.test(input.numbers)) {
            validators.numbers.show = true;
            this.setState({ validators });
            isValid = false;
        } else {
            validators.numbers.show = false;
            this.setState({ validators });
        }

        // lucky
        reg = /^[0-9]{1,2}([,.][0-9]{1,2})?$/g;
        if (!reg.test(input.lucky)) {
            validators.luckys.show = true;
            this.setState({ validators });
            isValid = false;
        } else {
            validators.luckys.show = false;
            this.setState({ validators });
        }

        // jackpot
        reg = /^-?\d*(\.\d+)?$/;
        if (!reg.test(input.jackpot) || input.jackpot === "") {
            validators.jackpot.show = true;
            this.setState({ validators });
            isValid = false;
        } else {
            validators.jackpot.show = false;
            this.setState({ validators });
        }

        return isValid;
    }

    doneMsg(type="edit"){
        if (type === "edit"){
            this.setState({
                isDone : true
            });
            setTimeout(()=>{
                this.setState({
                    isDone : false
                });
            },3000);
        } else {
            this.setState({
                isDoneAdd : true
            });
            setTimeout(()=>{
                this.setState({
                    isDoneAdd   : false,
                    open        : false
                });
            },3000);
        }
    }

    saveDraw(e){

        let lottery = 'eurojackpot';
        let params = this.mapperJackpotSave(this.state.inputEdit,"edit");

        let dateFormat = this.getDateFormatToString(new Date(this.state.inputEdit.date));
        let bodyFormData = objectToFormData(params);

        if (this.validator(this.state.inputEdit))
            axios.post('/admin/results/'+lottery+"/"+dateFormat,bodyFormData).then((response)=>{
                if(response.data.result === "Ok"){
                    this.doneMsg();
                    this.getResults();
                }
            })
            .catch((error) => {
                console.log(error);
            });
    }

    getResults(){
        let lottery = 'eurojackpot';
        axios.get('/admin/results/'+lottery).then((response)=>{
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

    initInsertDraws(){
        let breakDowns = [];
        breakDowns.push({id : 1,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 2,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 3,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 4,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 5,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 6,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 7,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 8,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 9,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 10,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 12,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 13,match : 0,prize : 0,winners : 0});
        breakDowns.push({id : 14,match : 0,prize : 0,winners : 0});

        let inputAdd = this.state.inputAdd;
        inputAdd.draws = breakDowns;

        this.setState({
            inputAdd
        });
    }

    componentWillMount() {
        this.getResults();
        this.initInsertDraws();
    }

    componentDidMount() {
        //this.textInput.focus();
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
                            if (!reg.test(newValue)) {
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

        //this.textInput.current.focus();

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
        let lottery = "eurojackpot";

        let params = this.mapperJackpotSave(this.state.inputAdd,"add");
        let bodyFormData = objectToFormData(params);

        if (this.validator(this.state.inputAdd)){
            axios.post('/admin/results/'+lottery,bodyFormData).then((response)=>{
                if(response.data.result === "Ok"){
                    this.doneMsg("add");
                    this.getResults();
                    this.setState({
                        isErrorAdd : false
                    })
                } else {
                    this.setState({
                        isErrorAdd : true
                    })
                }
            })
                .catch((error) => {
                    this.setState({
                        isErrorAdd : true
                    })
                });
            /*this.setState({
                open : false
            });*/
        }
    }

    setJackpot(e, type="edit"){

        if (type === "edit"){
            let inputEdit = this.state.inputEdit;
            inputEdit.jackpot = e.target.value;

            this.setState({
                inputEdit
            });
        } else {
            let inputAdd = this.state.inputAdd;
            inputAdd.jackpot = e.target.value;

            this.setState({
                inputAdd
            });
        }
    }

    setNumbers(e,type="edit"){
        if (type === "edit"){
            let inputEdit = this.state.inputEdit;
            inputEdit.numbers = e.target.value;

            this.setState({
                inputEdit
            });
        } else {
            let inputAdd = this.state.inputAdd;
            inputAdd.numbers = e.target.value;

            this.setState({
                inputAdd
            });
        }
    }

    setLucky(e,type="edit"){
        if (type === "edit"){
            let inputEdit = this.state.inputEdit;
            inputEdit.lucky = e.target.value;

            this.setState({
                inputEdit
            });
        } else {
            let inputAdd = this.state.inputAdd;
            inputAdd.lucky = e.target.value;

            this.setState({
                inputAdd
            });
        }

    }

    /* section add*/

    setDate(date){

        let inputAdd = this.state.inputAdd;
        inputAdd.date = date;

        this.setState({
            inputAdd
        })
    }

    addSection(){
        return <div>
            { this.state.isDoneAdd ?  <div className={`${bootstrap.alert} ${bootstrap["alert-success"]}`} role="alert">
                Done!
                </div> : '' }
            { this.state.isErrorAdd ?  <div className={`${bootstrap.alert} ${bootstrap["alert-danger"]}`} role="alert">
                Error when trying to register
                </div> : '' }
            <form>
                <div className={bootstrap["form-row"]}>
                    <input type="hidden" value={this.state.inputAdd.id} onChange={() => {}}/>
                    <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                        <label htmlFor="inputDate">Date</label>
                        <div >
                            {/*https://react-day-picker.js.org/examples/input*/}
                            <DayPickerInput
                                onDayChange={day => {this.setDate(day)}}
                                id="inputDate"
                                value={this.state.inputAdd.date} disabled={"disabled"}
                                formatDate={formatDate}
                                parseDate={parseDate}
                                format='D MMMM YYYY'
                                placeholder={`${formatDate(new Date(), 'D MMMM YYYY', 'en')}`}
                                dayPickerProps={{
                                    locale: 'en',
                                    localeUtils: MomentLocaleUtils,
                                }}
                            />
                        </div>
                    </div>
                    <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                        <label htmlFor="inputNumbers">Numbers</label>
                        <input type="text" className={bootstrap["form-control"]} id="inputNumbers" placeholder="Numbers" value={this.state.inputAdd.numbers} onChange={(e) => {this.setNumbers(e,"add")}}/>
                        {this.state.validators.numbers.show ?
                            <div className="alert alert-danger in" role="alert">
                                <strong>{ this.state.validators.numbers.text }</strong>
                            </div> : ''
                        }
                    </div>
                    <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                        <label htmlFor="inputJackpot">Jackpot</label>
                        <input type="text" className={bootstrap["form-control"]} id="inputJackpot" placeholder="Jackpot" value={this.state.inputAdd.jackpot} onChange={(e) => {this.setJackpot(e,"add")}}/>
                        {this.state.validators.jackpot.show ?
                            <div className="alert alert-danger in" role="alert">
                                <strong>{ this.state.validators.jackpot.text }</strong>
                            </div> : ''
                        }
                    </div>
                    <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                        <label htmlFor="inputLucky">Lucky</label>
                        <input type="text" className={bootstrap["form-control"]} id="inputLucky" placeholder="Lucky" value={this.state.inputAdd.lucky} onChange={(e) => {this.setLucky(e,"add")}}/>
                        {this.state.validators.luckys.show ?
                            <div className="alert alert-danger in" role="alert">
                                <strong>{ this.state.validators.luckys.text }</strong>
                            </div> : ''
                        }
                    </div>
                </div>
        </form>
            <br/>
            <BootstrapTable
                keyField='id'
                data={ this.state.inputAdd.draws }
                columns={ this.drawTable() }
                cellEdit={ cellEditFactory({ mode: 'click' }) }
                pagination={ paginationFactory() }
            />
        </div>
    }

    editSection(){
        let editSection = '';
        if (this.state.isShowEditSection){
            editSection = <div >
                <h2 className="sub-title purple">Edit draw</h2>
                <form>
                    { this.state.isDone ?  <div className={`${bootstrap.alert} ${bootstrap["alert-success"]}`} role="alert">
                        Done!
                    </div> : '' }
                    <div className={bootstrap["form-row"]}>
                        <input type="hidden" value={this.state.inputEdit.id} onChange={() => {}}/>
                        <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                            <label htmlFor="inputDate">Date</label>
                            <input type="text" className={bootstrap["form-control"]} id="inputDate" placeholder="Date" value={this.state.inputEdit.date} onChange={() => {}} disabled={true} />
                        </div>
                        <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                            <label htmlFor="inputNumbers">Numbers</label>
                            <input type="text" className={bootstrap["form-control"]} id="inputNumbers" placeholder="Numbers" value={this.state.inputEdit.numbers} onChange={(e) => {this.setNumbers(e)}} autoFocus/>
                            {this.state.validators.numbers.show ?
                                <div className="alert alert-danger in" role="alert">
                                    <strong>{ this.state.validators.numbers.text }</strong>
                                </div> : ''
                            }
                        </div>
                        <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                            <label htmlFor="inputJackpot">Jackpot</label>
                            <input type="text" className={bootstrap["form-control"]} id="inputJackpot" placeholder="Jackpot" value={this.state.inputEdit.jackpot} onChange={(e) => (this.setJackpot(e))}/>
                            {this.state.validators.jackpot.show ?
                                <div className="alert alert-danger in" role="alert">
                                    <strong>{ this.state.validators.jackpot.text }</strong>
                                </div> : ''
                            }
                        </div>
                        <div className={`${bootstrap["form-group"]} ${bootstrap["col-md-3"]}` }>
                            <label htmlFor="inputLucky">Lucky</label>
                            <input type="text" className={bootstrap["form-control"]} id="inputLucky" placeholder="Lucky" value={this.state.inputEdit.lucky} onChange={(e) => {this.setLucky(e)}}/>
                            {this.state.validators.luckys.show ?
                                <div className="alert alert-danger in" role="alert">
                                    <strong>{ this.state.validators.luckys.text }</strong>
                                </div> : ''
                            }
                        </div>
                    </div>
                </form>
                <div>
                    <div className={bootstrap.row}>
                        <div className={bootstrap["col-md-6"]}>
                            <a className="left btn btn-danger defaultColor" onClick={(e) => this.cancelEdit(e)}>Cancel</a>
                        </div>
                        <div className={bootstrap["col-md-6"]}>
                            <a className="right btn btn-primary defaultColor" onClick={(e) => this.saveDraw(e)}>Save</a>
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
                            <a className="right btn btn-primary defaultColor" onClick={(e) => this.saveDraw(e)}>Save</a>
                        </div>
                    </div>
                    <br />
                </div>
        }
        return editSection;
    }

    render() {

        let closeModal = () => this.setState({ open: false });

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
