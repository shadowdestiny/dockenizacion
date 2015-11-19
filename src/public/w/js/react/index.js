var EuroMillionsNumber = React.createClass({
    getDefaultProps: function()
    {
        return {
            selected: false
        }
    },
    propTypes: {
        number: React.PropTypes.number.isRequired,
        selected: React.PropTypes.bool,
        onNumberClick: React.PropTypes.func.isRequired
    },
    render: function () {
        var class_name = this.props.selected ? "btn gwp n" + this.props.number + " active" : "btn gwp n" + this.props.number;
        var button = <a className={class_name} onClick={this.props.onNumberClick.bind(null, this.props.number)} href="javascript:void(0);">{this.props.number}</a>;
        return (<li className="col20per not">{button}</li>);
    }
});

var EuroMillionsStar = React.createClass({
    render: function () {
        var number = this.props.number;
        var class_name = this.props.selected ? 'ico s' + number + ' active' : 'ico s' + number;
        return (
            <li className={this.props.columnClass}><a href="javascript:void(0);" className={class_name}>
                <svg className="vector v-star-out"
                     dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}}/>
                <svg className="vector v-star"
                     dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}}/>
                <span className="txt">{number}</span></a></li>
        );
    }
});

var EuroMillionsLineRow = React.createClass({
    render: function () {
        var numbers = [];
        var selected = false;
        var selected_numbers = this.props.selectedNumbers.numbers;
        var onNumberClick = this.props.onNumberClick;
        this.props.numbers.forEach(function (number) {
            selected = selected_numbers.indexOf(number) != -1;
            numbers.push(<EuroMillionsNumber onNumberClick={onNumberClick} number={number} key={number} selected={selected}/>);
        });
        return (
            <ol className="no-li cols not">
                {numbers}
            </ol>
        );
    }
});

var EuroMillionsLineStarsRow = React.createClass({
    render: function () {
        var numbers = [];
        var selected = false;
        var selected_numbers = this.props.selectedNumbers.stars ? this.props.selectedNumbers.stars : [];
        var class_name = "no-li cols not" + this.props.extraClass;
        var column_class = this.props.columnClass;
        this.props.numbers.forEach(function (number) {
            selected = selected_numbers.indexOf(number) != -1;
            numbers.push(<EuroMillionsStar number={number} key={number} selected={selected}
                                           columnClass={column_class}/>);
        });
        return (
            <ol className={class_name}>
                {numbers}
            </ol>
        );
    }
});

var EuroMillionsLine = React.createClass({
    getInitialState: function () {
        return {
            selectedNumbers: {
                'numbers': [],
                'stars': []
            }
        };
    },
    componentDidMount: function() {
        //sacar los values de las cookies
    },
    handleClickOnNumber: function (number) {
        if (typeof number != 'undefined') {
            var position = this.state.selectedNumbers.numbers.indexOf(number);
            if (position == -1) {
                this.state.selectedNumbers.numbers.push(number);
            } else {
                this.state.selectedNumbers.numbers.splice(position, 1);
            }
            this.setState(this.state);
        }
    },
    handleClickOnStar: function (star) {
        if (typeof star != 'undefined') {
            var position = this.state.selectedNumbers.stars.indexOf(star);
            if (position == -1) {
                this.state.selectedNumbers.stars.push(star);
            } else {
                this.state.selectedNumbers.stars.splice(position, 1);
            }
        }
    },

    render: function () {
        var rows = [];
        var linenumber = this.props.lineNumber + 1;
        for (var i = 1; i <= 50; i = i + j) {
            var row = [];
            for (var j = 0; j < this.props.numberPerLine; j++) {
                row.push(i + j)
            }
            rows.push(<EuroMillionsLineRow numbers={row} onNumberClick={this.handleClickOnNumber}
                                           selectedNumbers={this.state.selectedNumbers} key={row[0]}/>);
        }
        var star_rows = [];
        var star_numbers = [];
        for (var k = 1; k <= 4; k++) {
            star_numbers.push(k);
        }
        star_rows.push(<EuroMillionsLineStarsRow numbers={star_numbers} selectedNumbers={this.state.selectedNumbers}
                                                 extraClass="" columnClass="col3 not" key="1"/>);
        star_numbers = [];
        for (; k <= 7; k++) {
            star_numbers.push(k);
        }
        star_rows.push(<EuroMillionsLineStarsRow numbers={star_numbers} selectedNumbers={this.state.selectedNumbers}
                                                 extraClass=" extra-pad" columnClass="col4 not" key="2"/>);
        star_numbers = [];
        for (; k <= 11; k++) {
            star_numbers.push(k);
        }
        star_rows.push(<EuroMillionsLineStarsRow numbers={star_numbers} selectedNumbers={this.state.selectedNumbers}
                                                 extraClass="" columnClass="col3 not" key="3"/>);

        return (
            <div>
                <h1 className="h3 blue center">Line {  linenumber }</h1>
                <div className="line center">
                    <svg dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-checkmark"></use>'}}
                         className="ico v-checkmark"/>
                    <div className="combo cols not">
                        <div className="col6 not random"><a className="btn gwy multiplay" href="javascript:void(0);">
                            <svg className="v-shuffle"
                                 dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-shuffle"></use>'}}/>
                        </a></div>
                    </div>
                    <div className="values">
                        <div className="numbers">
                            {rows}
                        </div>
                        <div className="stars">
                            {star_rows}
                        </div>
                    </div>
                </div>
            </div>

        );
    }
});

for (var i = 0; i <= 5; i++) {
    var selected_numbers = eval('typeof selected_numbers_' + i) != 'undefined' ? eval('selected_numbers_' + i) : {};
    ReactDOM.render(
        <EuroMillionsLine numberPerLine="5" lineNumber={i}/>,
        document.getElementById('num_' + i)
    );
}
