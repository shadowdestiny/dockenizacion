import React, { Component } from 'react'
import PropTypes from 'prop-types'

import { BetLine } from './bet-line'
import { Ticket } from './ticket'
import { SvgIcon } from './svg-icon'
import { getRandomNumbers } from './utils'
import PowerPlayCheckbox from '../../components/PowerPlayCheckbox'
import {
  BUNDLE_CHECKED,
  BUNDLE_UNCHECKED,
  TICKET_MAX_NUMBER,
  TICKET_MAX_STAR_NUMBER,
  BET_NUMBERS_COUNT,
  BET_STARS_COUNT,
  GAME_MODE_POWERBALL,
  GAME_MODE_EUROMILLIONS,
} from './constants'

/**
 * Root application component for mobile view
 */
export default class MobilePlayApp extends Component {

  static propTypes = {
    /**
     * game mode. Two game modes supported which have minor differences between each other
     */
    mode : PropTypes.oneOf([GAME_MODE_POWERBALL, GAME_MODE_EUROMILLIONS]),
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
    /**
     * submission handler
     */
    onSubmit : PropTypes.func,
    /**
     * This prop will be submitted on checkout
     */
    playDate : PropTypes.string,

    powerPlayPrice: PropTypes.number,
  }

  constructor (props) {

    super(props)
    this.state = {}
    this.state = this.getStartingState(props)
  }

  /**
   * getStartingState - return initial state object basing on props
   *
   * @param  {object} props current component props
   * @return {void}
   */
  getStartingState (props) {
    const activeBundle = props.discountLines.find(l => l.checked == BUNDLE_CHECKED)
    const drawsNumber  = activeBundle ? activeBundle.draws : 1

    const betsFromStorage = JSON.parse(localStorage.getItem(this.getStorageKey())) || []
    const state = {
      drawsNumber,
      showTicket : null,
      bets : betsFromStorage.filter(b => b.numbers.length && b.stars.length),
      powerPlayEnabled : this.state.powerPlayEnabled || false,
      powerPlayPrice: this.state.powerPlayPrice || 0,
    }

    return state
  }

  getStorageKey () {
    const storageKeys = {
      [GAME_MODE_POWERBALL] : 'pb_bat_line',
      [GAME_MODE_EUROMILLIONS] : 'bet_line',
    }
    return storageKeys[this.props.mode]
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

  /**
   * renderMainLayout - renders main layout of the mobile view which contains
   * CTAs for adding/removing/editing of lines, choosing of discount bundle and
   * submission.
   *
   * @return {ReactElement}  markup
   */
  renderMainLayout () {
    const {
      nextDrawFormat,
      discountLines,
      translations,
      currencySymbol,
      mode,
    } = this.props

    const {
      drawsNumber,
      bets,
      powerPlayEnabled,
      powerPlayPrice,
    } = this.state

    const selectedBundle = this.findBundle(drawsNumber)
    const canSubmit = !!bets.length
    const showPowerCheckbox = mode == GAME_MODE_POWERBALL

    return (
      <div className="main-layout">

        <div className="bets-section">
          {bets.map(this.renderBetRow)}
          <button className="btn btn--random" onClick={this.addRandomLine}>
            {translations.addRandomLineBtn}
          </button>
          <button className="btn btn--pick" onClick={() => this.showTicket()}>
            {translations.pickYourNumbersBtn}
          </button>
        </div>

        {mode == GAME_MODE_POWERBALL
          ? <PowerPlayCheckbox
              checked={powerPlayEnabled}
              onChange={this.togglePowerPlay}
              translations={translations}
            />
            : null}

        <div className="draws-section">
          <div className="section-title">
            {translations.drawsSectionTitle}
          </div>
          <div className="section-sub-title">
            {translations.drawsSectionSubtitle}
          </div>
          <div className="buttons">
            {discountLines.map(item => {
              const isChecked = this.state.drawsNumber == item.draws
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
          {/*{this.state.powerPlayEnabled ? this.getLine().toFixed(2) : '' }*/}
        <div className="price-per-line">
            {currencySymbol} {this.getLine()} {selectedBundle ? selectedBundle.price_description : ''}
        </div>

        {drawsNumber == 1
          ? <div className="buy-for-date">
              {translations.buyForDraw} {nextDrawFormat}
            </div>
          : null}
        <div className="actions-section">
          <div className={`btn btn--total ${!canSubmit ? 'btn-disabled' : ''}`} onClick={this.onSubmit}>
            {currencySymbol} {this.getTotal().toFixed(2)}
          </div>
          <div className={`btn btn--next ${!canSubmit ? 'btn-disabled' : ''}`} onClick={this.onSubmit}>
            {translations.txtNextButton}
          </div>
        </div>
      </div>
    )
  }

  /**
   * Renders single bet row which contains of the actual bet numbers and CTAs
   * to edit/remove the bet
   *
   * @return {ReactElement}  marckup
   */
  renderBetRow = (bet, i) => {
    const {numbers, stars} = bet
    return (
      <div className="row" key={i}>
        <BetLine numbers={numbers} stars={stars} />
        <button className="btn btn--edit" onClick={() => this.showTicket(i)}>
          <SvgIcon iconName="v-pencil" />
        </button>
        <button className="btn btn--del" onClick={() => this.dropLine(i)}>
          <SvgIcon iconName="v-cross" />
        </button>
      </div>
    )
  }

  /**
   * renderTicket - renders ticket editing mode
   *
   * @return {ReactElement}  marckup
   */
  renderTicket () {
    const { showTicket, bets, drawsNumber } = this.state
    const bet = bets[showTicket] || {numbers : [], stars : []}
    const {numbers, stars} = bet
    const { translations, nextDrawFormat, mode } = this.props

    return (
      <div className="ticket-layout">
        <Ticket
          numbers={numbers}
          stars={stars}
          onCancel={this.hideTicket}
          onSubmit={(numbers, stars) => this.editLine(showTicket, numbers, stars)}
          translations={translations}
          nextDrawFormat={drawsNumber == 1 ? nextDrawFormat : null}
          gameMode={mode}
        />
      </div>
    )
  }

  togglePowerPlay = (enabled) => {
    this.setState({ powerPlayEnabled : enabled })
  }

  /**
   * selectBundle - handler for selecting one of available discount bundles
   *
   * @param  {Number} drawsNumber draws number
   * @return {void}
   */
  selectBundle (drawsNumber) {
      this.setState({ drawsNumber })
  }

  /**
   * findBundle - finds a particular discount bundle by the given draws number
   *
   * @param  {Number} draws draws number
   * @return {object}       item of the list this.props.discountLines (see propTypes for details)
   */
  findBundle (draws) {
    return this.props.discountLines.find(l => l.draws == draws)
  }

  /**
   * getTotal - reeturn total price taking into account discount bundle selected,
   * and amount of bets placed
   *
   * @return {Number}  total price
   */
  getTotal () {
    const { drawsNumber, bets } = this.state
    const bundle = this.findBundle(drawsNumber)
    var powerTotal = 0;

      if (this.state.powerPlayEnabled) {
          powerTotal = bets.length * this.props.powerPlayPrice * drawsNumber
      }

    if (bundle) {
      return bundle.singleBetPriceWithDiscount / 100 * bets.length * drawsNumber + powerTotal
    }
    return 0
  }

    getLine () {
        const { drawsNumber, bets } = this.state
        const selectedBundle = this.findBundle(drawsNumber)

        // return selectedBundle.singleBetPriceWithDiscount / 100) + this.props.powerPlayPrice).toFixed(2)
        if (this.state.powerPlayEnabled) {
            return (parseFloat(selectedBundle.singleBetPriceWithDiscount / 100) + parseFloat(this.props.powerPlayPrice)).toFixed(2);
            // powerTotal = bets.length * this.props.powerPlayPrice * drawsNumber;
        } else {
            return (selectedBundle.singleBetPriceWithDiscount / 100).toFixed(2);
        }

    }

  /**
   * addRandomLine - generates random line and places the bet
   *
   * @return {void}
   */
  addRandomLine = () => {
    const { mode } = this.props
    const numbers = getRandomNumbers(TICKET_MAX_NUMBER[mode], BET_NUMBERS_COUNT[mode])
    const stars = getRandomNumbers(TICKET_MAX_STAR_NUMBER[mode], BET_STARS_COUNT[mode])
    const bets = [...this.state.bets]
    bets.push({ numbers, stars })
    this.saveBets(bets)
  }

  /**
   * showTicket - enables ticket editing mode
   *
   * @param  {Number} i = -1 index of the interested bet in the list of bets placed.
   *                         Value -1 is used for opening of a clean ticket
   * @return {void}
   */
  showTicket (i = -1) {
    this.setState({ showTicket : i })
  }

  /**
   * hideTicket - switches the app back to main layout (closes the ticket)
   *
   * @return {void}
   */
  hideTicket = () => {
    this.setState({ showTicket : null })
  }

  /**
   * editLine - saves changes in bets
   *
   * @param  {Number} i              index of the bet been changed (-1 means new bet)
   * @param  {Array<Number>} numbers List of regular ticket numbers chosen
   * @param  {Array<Number>} stars   List of star ticket numbers chosen
   * @return {void}
   */
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

  /**
   * dropLine - removes bet per specified index in the list of existing bets
   *
   * @param  {Number} i bet index
   * @return {void}
   */
  dropLine (i) {
    const bets = [...this.state.bets]
    bets.splice(i, 1)
    this.saveBets(bets)
  }

  /**
   * saveBets - common method for mutating bets list, which updates the state and
   * stores the list in localStorage
   *
   * @param  {Array} bets bets list
   * @return {void}
   */
  saveBets (bets) {
    localStorage.setItem(this.getStorageKey(), JSON.stringify(bets))
    this.setState({ bets })
  }

  /**
   * onSubmit - The handler sends selection data to the server similar to the
   * method in component `EuroMillionsBoxBottomAction`
   *
   * @return {void}
   */
  onSubmit = () => {
    const {
      drawsNumber,
      bets,
      powerPlayEnabled,
    } = this.state

    const { playDate, mode } = this.props
    const powerPlay = powerPlayEnabled ? '1' : '0'
    // suppress if no bets placed
    if (!bets.length) {
      return
    }

    if (this.props.onSubmit) {
      this.props.onSubmit({ drawsNumber, bets })
    }

    let postData = ''
    bets.forEach((bet, i) => {
      if (bet.numbers.length == BET_NUMBERS_COUNT[mode] && bet.stars.length == BET_STARS_COUNT[mode]) {
        postData += `bet[${i}]=${bet.numbers},${bet.stars}&`
      }
    })
    // TODO: sort out deprecated and unused params
    postData += `draw_days=1&frequency=${drawsNumber}&draw_day_play=2&start_draw=${playDate}&power_play=${powerPlay}&game_mode=${mode}`
    ajaxFunctions.playCart(postData)
  }

}
