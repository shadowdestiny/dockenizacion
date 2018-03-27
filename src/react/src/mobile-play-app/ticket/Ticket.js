import React, { Component } from 'react'
import PropTypes from 'prop-types'
import range from 'lodash/range'

import { TicketNumber } from '../ticket-number'
import { SvgIcon } from '../svg-icon'
import { getRandomNumbers, easeOutQuart } from '../utils'

import {
  TICKET_NUMBER_TYPE_REGULAR,
  TICKET_NUMBER_TYPE_STAR,
  BET_NUMBERS_COUNT,
  BET_STARS_COUNT,
  TICKET_MAX_NUMBER,
  TICKET_MAX_STAR_NUMBER,
  GAME_MODE_EUROMILLIONS,
  GAME_MODE_POWERBALL,
} from '../constants'

/**
 * Component which is responsible for rendering of ticket editing mode, where
 * user can select/unselect ticket numbers.
 * It is used for new lines and for existing lines (edit mode)
 */
export default class Ticket extends Component {

  static propTypes = {
    /**
     * list of regular numbers selected
     */
    numbers  : PropTypes.arrayOf(PropTypes.number).isRequired,
    /**
     * list of star numbers selected
     */
    stars    : PropTypes.arrayOf(PropTypes.number).isRequired,
    /**
     * submission handler
     */
    onSubmit : PropTypes.func,
    /**
     * cancel handler (used for exiting from ticket editing mode)
     */
    onCancel : PropTypes.func,
    /**
     * List of translation variables
     */
    translations : PropTypes.object,
    /**
     * formatted Date/time of next draw. The corresponding block will be hidden if no value passed
     */
    nextDrawFormat : PropTypes.string,

    gameMode : PropTypes.oneOf([GAME_MODE_POWERBALL, GAME_MODE_EUROMILLIONS]),
  }

  constructor (props) {
    super(props)

    this.state = {
      numbers : [...props.numbers],
      stars : [...props.stars],
    }
  }

  render () {
    const { translations, nextDrawFormat, gameMode } = this.props

    const {
      numbers,
      stars,
    } = this.state

    const regNumbers = range(1, TICKET_MAX_NUMBER[gameMode] + 1)
    const starNumbers = range(1, TICKET_MAX_STAR_NUMBER[gameMode] + 1)

    const canSubmit = numbers.length == BET_NUMBERS_COUNT[gameMode] && stars.length == BET_STARS_COUNT[gameMode]

    return (
      <div className="ticket">
        <div className="clear-button-big-block">
          <button className="btn btn--clear-big" onClick={this.onCancel}>
            <SvgIcon iconName="v-cross" />
          </button>
        </div>

        <div className="ticket-actions">
          <button className="btn btn--random-second" onClick={this.randomizeAnimated}>
            <SvgIcon iconName="v-shuffle" />
            {translations.mobTicketRandomizeBtn}
          </button>
          <button className="btn btn--clear-small" onClick={this.clear}>
            <SvgIcon iconName="v-cross" />
            {translations.mobTicketClearBtn}
          </button>
        </div>

        <div className="numbers">
          <div className="numbers-section regular">
            {regNumbers.map(number =>
              <TicketNumber
                key={number}
                number={number}
                type={TICKET_NUMBER_TYPE_REGULAR}
                selected={numbers.indexOf(number) != -1}
                onClick={this.toggleRegNumber}
              />
            )}
          </div>

          <div className="numbers-section stars">
            {starNumbers.map(number =>
              <TicketNumber
                key={number}
                number={number}
                type={TICKET_NUMBER_TYPE_STAR}
                selected={stars.indexOf(number) != -1}
                onClick={this.toggleStarNumber}
              />
            )}
          </div>
        </div>

        {nextDrawFormat
          ? <div className="buy-for-date">
              {translations.buyForDraw} {nextDrawFormat}
            </div>
          : null
        }

        <button
          className={`btn btn--next ${!canSubmit ? 'btn-disabled' : ''}`}
          onClick={this.onSubmit}
        >
          {translations.mobTicketSubmitBtn}
        </button>
      </div>
    )
  }

  /**
   * toggleNumber - modifies list of selected numbers by removing the specified number
   * if it is present in the list or adding it to the list otherwise.
   *
   * @param  {Array<Number>} selected list of selected numbers
   * @param  {Number} number          number to toggle
   * @param  {Number} maxCount        maximum count of selected numbers in order to prevent overflowing
   * @return {Array<Number>}          modified list of selected numbers
   */
  toggleNumber (selected, number, maxCount) {
    selected = [...selected]
    const idx = selected.indexOf(number)
    if (idx != -1) {
      selected.splice(idx, 1)
    } else {
      if (selected.length < maxCount) {
        selected.push(number)
        selected.sort((a, b) => a - b)
      }
    }
    return selected
  }

  /**
   * toggleRegNumber - toggles specified number in the list of regular numbers
   *
   * @param  {SytheticEvent} e   click event
   * @param  {Number} number     number to toggle
   * @return {void}
   */
  toggleRegNumber = (e, number) => {
    const { gameMode } = this.props
    this.setState({
      numbers : this.toggleNumber(this.state.numbers, number, BET_NUMBERS_COUNT[gameMode])
    })
  }

  /**
   * toggleStarNumber - toggles specified number in the list of star numbers
   *
   * @param  {SytheticEvent} e   click event
   * @param  {Number} number     number to toggle
   * @return {void}
   */
  toggleStarNumber = (e, number) => {
    const { gameMode } = this.props
    this.setState({
      stars : this.toggleNumber(this.state.stars, number, BET_STARS_COUNT[gameMode])
    })
  }

  /**
   * onCancel - click handler for the Cancel button
   *
   * @param  {SytheticEvent} e   click event
   * @return {void}
   */
  onCancel = (e) => {
    if (this.props.onCancel) {
      this.props.onCancel(e)
    }
  }

  /**
   * onSubmit - click handler for the `Done` button.
   * Note that the props.onSubmit callback is called only if appropriate amount of
   * numbers was selected
   *
   * @param  {SytheticEvent} e   click event
   * @return {void}
   */
  onSubmit = (e) => {
    const { stars, numbers } = this.state
    const { gameMode } = this.props

    if (numbers.length < BET_NUMBERS_COUNT[gameMode] || stars.length < BET_STARS_COUNT[gameMode])  {
      return
    }

    if (this.props.onSubmit) {
      this.props.onSubmit(numbers, stars)
    }
  }

  /**
   * clear - cleares selection of the current ticket
   *
   * @param  {SytheticEvent} e   click event
   * @return {void}
   */
  clear = () => {
    this.setState({
      numbers : [],
      stars : [],
    })
  }

  /**
   * randomize - shuffles numbers selection within the ticket
   *
   * @return {void}
   */
  randomize = () => {
    const { gameMode } = this.props
    const numbers = getRandomNumbers(TICKET_MAX_NUMBER[gameMode], BET_NUMBERS_COUNT[gameMode])
    const stars = getRandomNumbers(TICKET_MAX_STAR_NUMBER[gameMode], BET_STARS_COUNT[gameMode])
    this.setState({ numbers, stars })
  }

  /**
   * randomizeAnimated - fires animation of numbers shuffling
   *
   * @return {void}
   */
  randomizeAnimated = () => {
    const iterations = 10
    const duration = 180
    this.animationStep = 0

    const clearAnimation = () => {
      if (this.animationTimer) {
        clearTimeout(this.animationTimer)
        this.animationStep = 0
        this.animationTimer = null
      }
    }
    const playAnimation = () => {
      if (this.animationStep >= iterations) {
        clearAnimation()
      } else {
        const time = easeOutQuart(this.animationStep / iterations) * duration
        this.animationTimer = setTimeout(playAnimation, time)
        this.animationStep ++
      }
      this.randomize()
    }
    clearAnimation()
    playAnimation()
  }
}
