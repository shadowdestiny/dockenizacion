import React, { Component } from 'react'
import PropTypes from 'prop-types'

import { BetLine } from './bet-line'
import { Ticket } from './ticket'
import { SvgIcon } from './svg-icon'
import { getRandomNumbers } from './utils'
import {
  BUNDLE_CHECKED,
  BUNDLE_UNCHECKED,
  TICKET_MAX_NUMBER,
  TICKET_MAX_STAR_NUMBER,
  BET_NUMBERS_COUNT,
  BET_STARS_COUNT,
} from './constants'

export default class MobilePlayApp extends Component {

  static propTypes = {
    /**
     * formatted Date/time of next draw
     */
    nextDrawFormat : PropTypes.string,
    /**
     * currency symbol for price formatting purposes
     */
    currencySymbol : PropTypes.string,
    /**
     * discount bundles depending on draws amount
     */
    discountLines : PropTypes.arrayOf(PropTypes.shape({
      draws                      : PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
      description                : PropTypes.string,
      price_description          : PropTypes.string,
      price                      : PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
      discount                   : PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
      checked                    : PropTypes.oneOf([BUNDLE_CHECKED, BUNDLE_UNCHECKED]),
      singleBetPrice             : PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
      singleBetPriceWithDiscount : PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    })),

    /**
     * List of translation variables
     */
    translations : PropTypes.object,

    onSubmit : PropTypes.func,
  }

  constructor (props) {
    super(props)
    this.state = this.getStartingState(props)
  }

  getStartingState (props) {
    const activeBundle = props.discountLines.find(l => l.checked == BUNDLE_CHECKED)
    const drawsNumber  = activeBundle ? activeBundle.draws : 1

    const betsFromStorage = JSON.parse(localStorage.getItem('bet_line')) || []

    const state = {
      drawsNumber,
      showTicket : null,
      bets : betsFromStorage.filter(b => b.numbers.length && b.stars.length),
    }

    return state
  }

  render () {
    const {
      showTicket
    } = this.state

    return (
      <div className="play-page-mobile">
        {showTicket !== null ? this.renderTicket() : this.renderMainLayout()}
      </div>
    )
  }

  renderMainLayout () {
    const {
      nextDrawFormat,
      discountLines,
      translations,
      currencySymbol,
    } = this.props

    const {
      drawsNumber,
      bets,
    } = this.state

    const selectedBundle = this.findBundle(drawsNumber)
    const canSubmit = !!bets.length

    return (
      <div className="main-layout">

        <div className="bets-section">
          {bets.map(this.renderBetRow)}
          <button className="btn" onClick={this.addRandomLine}>
            Add random line
          </button>
          <button className="btn" onClick={() => this.showTicket()}>
            Pick your numbers
          </button>
        </div>

        <div className="draws-section">
          <div className="section-title">
            How many draw would you like to play?
          </div>
          <div className="section-sub-title">
            *Subscribe for more draws and get a discount per lines
          </div>
          <div className="buttons">
            {discountLines.map(item => {
              const isChecked = this.state.drawsNumber == item.draws
              console.log(item, item.singleBetPrice > item.singleBetPriceWithDiscount);
              return (
                <div key={item.draws} className={`bundle-btn ${isChecked ? "active" : ''}`}>
                  <div
                    className="btn"
                    onClick={() => this.selectBundle(item.draws)}
                  >
                    {item.draws}
                    {item.singleBetPrice > item.singleBetPriceWithDiscount ? '*' : ''}
                  </div>
                </div>
              )
            })}
          </div>
        </div>

        <div className="price-per-line">
          {selectedBundle ? selectedBundle.price_description : ''}
        </div>

        {drawsNumber == 1
          ? <div className="buy-for-date">
              {translations.buyForDraw} {nextDrawFormat}
            </div>
          : null}
        <div className="actions-section">
          <div className={`btn ${!canSubmit ? 'btn-disabled' : ''}`} onClick={this.onSubmit}>
            {currencySymbol} {this.getTotal().toFixed(2)}
          </div>
          <div className={`btn ${!canSubmit ? 'btn-disabled' : ''}`} onClick={this.onSubmit}>
            {translations.txtNextButton}
          </div>
        </div>
      </div>
    )
  }

  renderBetRow = (bet, i) => {
    const {numbers, stars} = bet
    return (
      <div className="row" key={i}>
        <BetLine numbers={numbers} stars={stars} />
        <button className="btn" onClick={() => this.showTicket(i)}>
          <SvgIcon iconName="v-pencil" />
        </button>
        <button className="btn" onClick={() => this.dropLine(i)}>
          <SvgIcon iconName="v-cross" />
        </button>
      </div>
    )
  }

  renderTicket () {
    const { showTicket, bets } = this.state
    const bet = bets[showTicket] || {numbers : [], stars : []}
    const {numbers, stars} = bet

    return (
      <div className="ticket-layout">
        <Ticket
          numbers={numbers}
          stars={stars}
          onCancel={this.hideTicket}
          onSubmit={(numbers, stars) => this.editLine(showTicket, numbers, stars)}
        />
      </div>
    )
  }

  selectBundle (drawsNumber) {
      this.setState({ drawsNumber })
  }

  findBundle (draws) {
    return this.props.discountLines.find(l => l.draws == draws)
  }

  getTotal () {
    const { drawsNumber, bets } = this.state
    const bundle = this.findBundle(drawsNumber)
    if (bundle) {
      return bundle.singleBetPriceWithDiscount / 100 * bets.length * drawsNumber
    }
    return 0
  }

  addRandomLine = () => {
    const numbers = getRandomNumbers(TICKET_MAX_NUMBER, BET_NUMBERS_COUNT)
    const stars = getRandomNumbers(TICKET_MAX_STAR_NUMBER, BET_STARS_COUNT)
    const bets = [...this.state.bets]
    bets.push({ numbers, stars })
    this.saveBets(bets)
  }

  showTicket (i = -1) {
    this.setState({ showTicket : i })
  }

  hideTicket = () => {
    this.setState({ showTicket : null })
  }

  editLine (i, numbers, stars) {
    const bets = [...this.state.bets]
    if (i == -1) {
      bets.push({ numbers, stars })
    } else {
      bets.splice(i, 1, { numbers, stars })
    }
    this.setState({ showTicket : null })
    this.saveBets(bets)
  }

  dropLine (i) {
    const bets = [...this.state.bets]
    bets.splice(i, 1)
    this.saveBets(bets)
  }

  saveBets (bets) {
    localStorage.setItem('bet_line', JSON.stringify(bets))
    this.setState({ bets })
  }

  onSubmit = () => {
    const {
      drawsNumber,
      bets
    } = this.state

    // suppress if no bets placed
    if (!bets.length) {
      return
    }

    if (this.props.onSubmit) {
      this.props.onSubmit({ drawsNumber, bets })
    }

    let postData = ''
    bets.forEach((bet, i) => {
      if (bet.numbers.length == BET_NUMBERS_COUNT && bet.stars.length == BET_STARS_COUNT) {
        postData += `bet[${i}]=${bet.numbers},${bet.stars}&`
      }
    })
    // TODO: sort out deprecated and unused params
    postData += `draw_days=1&frequency=${drawsNumber}&draw_day_play=2`
    ajaxFunctions.playCart(postData)
  }

}
