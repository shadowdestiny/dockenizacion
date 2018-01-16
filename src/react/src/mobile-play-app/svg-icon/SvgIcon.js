import React, { Component } from 'react'
import PropTypes from 'prop-types'


/**
 * Component-helper for inserting svg-icons
 */
export default class SvgIcon extends Component {

  static propTypes = {
    /**
     * id of interested symbol in the /w/svg/icon.svg SVG file
     */
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
