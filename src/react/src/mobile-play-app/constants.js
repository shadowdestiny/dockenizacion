export const GAME_MODE_EUROMILLIONS = 'euromillions'
export const GAME_MODE_POWERBALL    = 'powerball'

export const BUNDLE_CHECKED   = 'active'
export const BUNDLE_UNCHECKED = ''

export const TICKET_MAX_NUMBER = {
  [GAME_MODE_EUROMILLIONS] : 50,
  [GAME_MODE_POWERBALL] : 69,
}
export const TICKET_MAX_STAR_NUMBER = {
  [GAME_MODE_EUROMILLIONS] : 12,
  [GAME_MODE_POWERBALL] : 26,
}

export const BET_NUMBERS_COUNT = {
  [GAME_MODE_EUROMILLIONS] : 5,
  [GAME_MODE_POWERBALL] : 5,
}
export const BET_STARS_COUNT = {
  [GAME_MODE_EUROMILLIONS] : 2,
  [GAME_MODE_POWERBALL] : 1,
}

export const TICKET_NUMBER_TYPE_REGULAR = 'regular'
export const TICKET_NUMBER_TYPE_STAR    = 'star'
