import React, { Component } from 'react'
import PropTypes from 'prop-types'
import range from 'lodash/range'

import { TicketNumber } from '../ticket-number'
import { SvgIcon } from '../svg-icon'
import { getRandomNumbers } from '../utils'

import {
  TICKET_NUMBER_TYPE_REGULAR,
  TICKET_NUMBER_TYPE_STAR,
  BET_NUMBERS_COUNT,
  BET_STARS_COUNT,
  TICKET_MAX_NUMBER,
  TICKET_MAX_STAR_NUMBER,
} from '../constants'

export default class Ticket extends Component {

  static propTypes = {
    numbers  : PropTypes.arrayOf(PropTypes.number).isRequired,
    stars    : PropTypes.arrayOf(PropTypes.number).isRequired,
    onSubmit : PropTypes.func,
    onCancel : PropTypes.func,
  }

  constructor (props) {
    super(props)

    this.state = {
      numbers : [...props.numbers],
      stars : [...props.stars],
    }
  }

  render () {
    const {
      numbers,
      stars,
    } = this.state

    const regNumbers = range(1, TICKET_MAX_NUMBER + 1)
    const starNumbers = range(1, TICKET_MAX_STAR_NUMBER + 1)

    const canSubmit = numbers.length == BET_NUMBERS_COUNT && stars.length == BET_STARS_COUNT

    return (
      <div className="ticket">
        <div>
          <button className="btn btn--clear-big" onClick={this.onCancel}>
            <SvgIcon iconName="v-cross" />
          </button>
        </div>

        <div className="ticket-actions">
          <button className="btn btn--random-second" onClick={this.randomize}>
            Randomize
          </button>
          <button className="btn btn--clear-small" onClick={this.clear}>
            <SvgIcon iconName="v-cross" />
            Clear
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
        
        <button
          className={`btn btn--next ${!canSubmit ? 'btn-disabled' : ''}`}
          onClick={this.onSubmit}
        >
          Done
        </button>
      </div>
    )
  }

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

  toggleRegNumber = (e, number) => {
    this.setState({
      numbers : this.toggleNumber(this.state.numbers, number, BET_NUMBERS_COUNT)
    })
  }

  toggleStarNumber = (e, number) => {
    this.setState({
      stars : this.toggleNumber(this.state.stars, number, BET_STARS_COUNT)
    })
  }

  onCancel = (e) => {
    if (this.props.onCancel) {
      this.props.onCancel(e)
    }
  }

  onSubmit = (e) => {
    const { stars, numbers } = this.state

    if (numbers.length < BET_NUMBERS_COUNT || stars.length < BET_STARS_COUNT)  {
      return
    }

    if (this.props.onSubmit) {
      this.props.onSubmit(numbers, stars)
    }
  }

  clear = () => {
    this.setState({
      numbers : [],
      stars : [],
    })
  }

  randomize = () => {
    const numbers = getRandomNumbers(TICKET_MAX_NUMBER, BET_NUMBERS_COUNT)
    const stars = getRandomNumbers(TICKET_MAX_STAR_NUMBER, BET_STARS_COUNT)
    this.setState({ numbers, stars })
  }
}
