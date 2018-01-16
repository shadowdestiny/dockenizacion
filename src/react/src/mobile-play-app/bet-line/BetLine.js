import React, { Component } from 'react'
import PropTypes from 'prop-types'
import range from 'lodash/range'

import { TicketNumber } from '../ticket-number'
import {
  TICKET_NUMBER_TYPE_REGULAR,
  TICKET_NUMBER_TYPE_STAR,
  BET_NUMBERS_COUNT,
  BET_STARS_COUNT,
} from '../constants'

export default class BetLine extends Component {

  static propTypes = {
    numbers : PropTypes.arrayOf(PropTypes.number).isRequired,
    stars   : PropTypes.arrayOf(PropTypes.number).isRequired,
  }

  render () {
    const {
      numbers,
      stars,
      ...other
    } = this.props

    return (
      <div className="bet-line" {...other}>
        {numbers.map((number, i) =>
          <TicketNumber
            key={`r${i}`}
            number={number}
            type={TICKET_NUMBER_TYPE_REGULAR}
          />
        )}
        {stars.map((number, i) =>
          <TicketNumber
            key={`s${i}`}
            number={number}
            type={TICKET_NUMBER_TYPE_STAR}
          />
        )}
      </div>
    )
  }
}
