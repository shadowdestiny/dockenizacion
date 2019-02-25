export const GAME_MODE_EUROMILLIONS = 'euromillions';
export const GAME_MODE_POWERBALL    = 'powerball';
export const GAME_MODE_MEGAMILLIONS = 'megamillions';
export const GAME_MODE_EUROJACKPOT = 'eurojackpot';
export const GAME_MODE_MEGASENA = 'megasena';

export const numberSets = {
  [GAME_MODE_POWERBALL]    : { maxStars : 1, highestNumber : 69, highestStar : 26 , maxNumbers : 5},
  [GAME_MODE_EUROMILLIONS] : { maxStars : 2, highestNumber : 50, highestStar : 12 , maxNumbers : 5},
  [GAME_MODE_MEGAMILLIONS] : { maxStars : 1, highestNumber : 70, highestStar : 25 , maxNumbers : 5},
  [GAME_MODE_EUROJACKPOT]  : { maxStars : 2, highestNumber : 50, highestStar : 10 , maxNumbers : 5},
  [GAME_MODE_MEGASENA] : { maxStars : 0, highestNumber : 60, highestStar : 0, maxNumbers : 6},
};