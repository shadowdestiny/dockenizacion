import React, { Component } from 'react'
import PropTypes from 'prop-types'
import ReactTooltip from "react-tooltip"


/**
 * Root application component for mobile view
 */
export default class PowerPlayCheckbox extends Component {

  static propTypes = {
    checked      : PropTypes.bool,
    onChange     : PropTypes.func,
    translations : PropTypes.shape({
      powerPlayCheck : PropTypes.string,
      powerPlayInfo  : PropTypes.string,
    }).isRequired,
  }

  constructor (props) {
    super(props)
    this.state = {
      checked : props.checked,
    }
  }

  componentWillReceiveProps (nextProps) {
    if (nextProps.checked != this.props.checked) {
      this.setState({ checked : nextProps.checked })
    }
  }

  render () {
    const { translations } = this.props
    const { checked } = this.state

    return (
      <div className={"power-play-check" + (checked ? ' checked' : '')} onClick={this.onClickHandler}>
        {translations.powerPlayCheck}
        <div
          className="info-icon"
          data-tip={translations.powerPlayInfo}
          data-for="info-icon"
          >!
          <ReactTooltip type="light" id='info-icon'/>
        </div>
      </div>
    )
  }

  onClickHandler = (e) => {
    const { checked } = this.state
    this.setState({ checked : !checked })
    if (this.props.onChange) {
      this.props.onChange(!checked)
    }
  }
}
