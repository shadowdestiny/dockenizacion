import React, { Component } from 'react'
import PropTypes from 'prop-types'

export default class SvgIcon extends Component {

  static propTypes = {
    iconName : PropTypes.string.isRequired,
  }

  render () {
    const {
      iconName,
      ...other
    } = this.props

    return (
      <svg className={`ico ${iconName}`} {...other}>
        <use xlinkHref={`/w/svg/icon.svg#${iconName}`}></use>
      </svg>
    )
  }
}
