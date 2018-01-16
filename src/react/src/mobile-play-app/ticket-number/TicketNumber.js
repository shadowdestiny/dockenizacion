import React, { Component } from 'react'
import PropTypes from 'prop-types'

import {
  TICKET_NUMBER_TYPE_REGULAR,
  TICKET_NUMBER_TYPE_STAR,
} from '../constants'

/**
 * Representation of a particular ticket number. The component is used in Ticket
 * and BetLine components
 */
export default class TicketNumber extends Component {

  static propTypes = {
    /**    
     * either this is a regular number or a star number
     */
    type     : PropTypes.oneOf([TICKET_NUMBER_TYPE_REGULAR, TICKET_NUMBER_TYPE_STAR]),
    /**
     * the actual number
     */
    number   : PropTypes.number.isRequired,
    /**
     * whether the number should be highlighted as selected
     */
    selected : PropTypes.bool,
    /**
     * click handler
     */
    onClick  : PropTypes.func,
  }

  static defaultProps = {
    type : TICKET_NUMBER_TYPE_REGULAR,
  }

  render () {
    const {
      type,
      number,
      selected,
      onClick,
      ...other
    } = this.props

    return (
      <div
        className={`ticket-number ${type} ${selected ? 'selected' : ''}`}
        onClick={this.onClick}
        {...other}
      >
        {number}
      </div>
    )
  }

  onClick = (e) => {
    if (this.props.onClick) {
      this.props.onClick(e, this.props.number)
    }
  }
}
