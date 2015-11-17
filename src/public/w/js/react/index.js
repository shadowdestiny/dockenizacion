var EuroMillionsNumber = React.createClass({
// El selected hay que ponerlo como propiedad de cada número
    render: function() {
        var button = this.props.selected ?
            <a className="btn gwp n1 active" href="javascript:void(0);">{this.props.number}</a> :
            <a className="btn gwp n1" href="javascript:void(0);">{this.props.number}</a>;
        return (<li className="col20per not">{button}</li>)
    }
});

var EuroMillionsLineRow = React.createClass({
    render: function() {
        var numbers = [];
        this.props.numbers.forEach(function(number) {
            numbers.push(<EuroMillionsNumber number={number} key={number} />);
        });
        return (
            <ol className="no-li cols not">
                {numbers}
            </ol>
        );
    }
});

var EuroMillionsLine = React.createClass({
    render: function() {
        var rows = [];
        var linenumber = this.props.lineNumber +1;
        for (var i=1; i<=this.props.maxRegularNumber; i=i+j) {
            var row = [];
            for (var j=0; j < this.props.numberPerLine; j++)
            {
                row.push(i+j)
            }
            rows.push(<EuroMillionsLineRow numbers={row} key={row[0]}/>);
        }
        return (
            <div>
                <h1 className="h3 blue center">Line {  linenumber }</h1>
                <div className="line center">
                    <svg dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-checkmark"></use>'}} className="ico v-checkmark" />
                    <div className="combo cols not">
                        <div className="col6 not random"><a className="btn gwy multiplay" href="javascript:void(0);"><svg className="v-shuffle" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-shuffle"></use>'}} /></a></div>
                    </div>
                    <div className="values">
                        <div className="numbers">
                           {rows}
                        </div>
                        <div className="stars">
                            <ol className="no-li cols not">
                                <li className="col3 not"><a href="javascript:void(0);" className="ico s1"><svg className="v-star-out" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}} /><svg className="v-star" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}} /><span className="txt">1</span></a></li>
                                <li className="col3 not"><a className="ico s2" href="javascript:void(0);"><svg className="v-star-out" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}} /><svg className="v-star" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}} /><span className="txt">2</span></a></li>
                                <li className="col3 not"><a className="ico s3" href="javascript:void(0);"><svg className="v-star-out" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}} /><svg className="v-star" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}} /><span className="txt">3</span></a></li>
                                <li className="col3 not"><a className="ico s4" href="javascript:void(0);"><svg className="v-star-out" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}} /><svg className="v-star" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}} /><span className="txt">4</span></a></li>
                            </ol>
                            <ol className="no-li cols extra-pad not">
                                <li className="col4 not"><a className="ico s5" href="javascript:void(0);"><svg className="v-star-out" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}} /><svg className="v-star" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}} /><span className="txt">5</span></a></li>
                                <li className="col4 not"><a className="ico s6" href="javascript:void(0);"><svg className="v-star-out" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}} /><svg className="v-star" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}} /><span className="txt">6</span></a></li>
                                <li className="col4 not"><a className="ico s7" href="javascript:void(0);"><svg className="v-star-out" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}} /><svg className="v-star" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}} /><span className="txt">7</span></a></li>
                            </ol>
                            <ol className="no-li cols not">
                                <li className="col3 not"><a className="ico s8" href="javascript:void(0);"><svg className="v-star-out" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}} /><svg className="v-star" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}} /><span className="txt">8</span></a></li>
                                <li className="col3 not"><a className="ico s9" href="javascript:void(0);"><svg className="v-star-out" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}} /><svg className="v-star" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}} /><span className="txt">9</span></a></li>
                                <li className="col3 not"><a className="ico s10" href="javascript:void(0);"><svg className="v-star-out" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}} /><svg className="v-star" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}} /><span className="txt">10</span></a></li>
                                <li className="col3 not"><a className="ico s11" href="javascript:void(0);"><svg className="v-star-out" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star-out"></use>'}} /><svg className="v-star" dangerouslySetInnerHTML={{__html: '<use xlink:href="/w/svg/icon.svg#v-star"></use>'}} /><span className="txt">11</span></a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        );
    }
});

for(var i=0; i<=5; i++) {
    ReactDOM.render(
        <EuroMillionsLine numberPerLine="5" maxRegularNumber="50" lineNumber={i} />,
        document.getElementById('num_'+i)
    );
}
