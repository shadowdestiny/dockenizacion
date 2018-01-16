import shuffle from 'lodash/shuffle'
import range from 'lodash/range'

export function getRandomNumbers (maxNumber, count) {
  return shuffle(range(1, maxNumber + 1))
    .slice(0, count)
    .sort((a, b) => a - b)
}
