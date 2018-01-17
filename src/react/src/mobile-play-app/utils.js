import shuffle from 'lodash/shuffle'
import range from 'lodash/range'

/**
 * getRandomNumbers - The helper returns list of randomly selected numbers of
 * desired length
 *
 * @param  {Number} maxNumber max number of range been used for the list generation (min = 1)
 * @param  {Number} count     desired output array length
 * @return {Array<Number>}    array of random numbers
 */
export function getRandomNumbers (maxNumber, count) {
  return shuffle(range(1, maxNumber + 1))
    .slice(0, count)
    .sort((a, b) => a - b)
}

/**
 * easeOutQuart - easing function
 *
 * @param  {Number} t
 * @return {Number} 
 */
export function easeOutQuart (t) {
  return 1-(--t)*t*t*t
}
