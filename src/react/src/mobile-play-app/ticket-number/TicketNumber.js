import React, { Component } from 'react'
import PropTypes from 'prop-types'

import {
  TICKET_NUMBER_TYPE_REGULAR,
  TICKET_NUMBER_TYPE_STAR,
} from '../constants'

export default class TicketNumber extends Component {

  static propTypes = {
    type     : PropTypes.oneOf([TICKET_NUMBER_TYPE_REGULAR, TICKET_NUMBER_TYPE_STAR]),
    number   : PropTypes.number.isRequired,
    selected : PropTypes.bool,
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
