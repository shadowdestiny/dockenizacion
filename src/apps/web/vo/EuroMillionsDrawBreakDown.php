<?php


namespace EuroMillions\web\vo;


use EuroMillions\shared\helpers\StringHelper;
use Money\Currency;
use Money\Money;

class EuroMillionsDrawBreakDown
{

    CONST NUMBER_OF_CATEGORIES = 17;

    protected $type;

    protected $breakdown;

    protected $category_one;

    protected $category_two;

    protected $category_three;

    protected $category_four;

    protected $category_five;

    protected $category_six;

    protected $category_seven;

    protected $category_eight;

    protected $category_nine;

    protected $category_ten;

    protected $category_eleven;

    protected $category_twelve;

    protected $category_thirteen;

    protected $category_fourteen;

    protected $category_fifteen;

    protected $category_sixteen;

    protected $category_seventeen;


    /**
     * @param array $breakdown
     */
    public function __construct(array $breakdown)
    {
        if (!is_array($breakdown)) {
            throw new \InvalidArgumentException("");
        }

//        if (count($breakdown) < self::NUMBER_OF_CATEGORIES) {
//            throw new \LengthException('Incorrect categories length from collection');
//        }
        $this->breakdown = $breakdown;
        $this->loadBreakDownData();
    }

    public function setCategoryOne($category_one)
    {
        $this->category_one = $category_one;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryOne()
    {
        return $this->category_one;
    }

    public function setCategoryTwo($category_two)
    {
        $this->category_two = $category_two;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryTwo()
    {
        return $this->category_two;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryThree()
    {
        return $this->category_three;
    }

    /**
     * @param mixed $category_three
     */
    public function setCategoryThree($category_three)
    {
        $this->category_three = $category_three;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryFour()
    {
        return $this->category_four;
    }


    public function setCategoryFour($category_four)
    {
        $this->category_four = $category_four;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryFive()
    {
        return $this->category_five;
    }

    /**
     * @param mixed $category_five
     */
    public function setCategoryFive($category_five)
    {
        $this->category_five = $category_five;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategorySix()
    {
        return $this->category_six;
    }

    /**
     * @param mixed $category_six
     */
    public function setCategorySix($category_six)
    {
        $this->category_six = $category_six;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategorySeven()
    {
        return $this->category_seven;
    }

    /**
     * @param mixed $category_seven
     */
    public function setCategorySeven($category_seven)
    {
        $this->category_seven = $category_seven;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryEight()
    {
        return $this->category_eight;
    }

    /**
     * @param mixed $category_eight
     */
    public function setCategoryEight($category_eight)
    {
        $this->category_eight = $category_eight;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryNine()
    {
        return $this->category_nine;
    }

    /**
     * @param mixed $category_nine
     */
    public function setCategoryNine($category_nine)
    {
        $this->category_nine = $category_nine;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryTen()
    {
        return $this->category_ten;
    }

    /**
     * @param mixed $category_ten
     */
    public function setCategoryTen($category_ten)
    {
        $this->category_ten = $category_ten;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryEleven()
    {
        return $this->category_eleven;
    }

    /**
     * @param mixed $category_eleven
     */
    public function setCategoryEleven($category_eleven)
    {
        $this->category_eleven = $category_eleven;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryTwelve()
    {
        return $this->category_twelve;
    }

    /**
     * @param mixed $category_twelve
     */
    public function setCategoryTwelve($category_twelve)
    {
        $this->category_twelve = $category_twelve;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryThirteen()
    {
        return $this->category_thirteen;
    }

    /**
     * @param mixed $category_thirteen
     */
    public function setCategoryThirteen($category_thirteen)
    {
        $this->category_thirteen = $category_thirteen;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryFourteen()
    {
        return $this->category_fourteen;
    }

    /**
     * @param mixed $category_fourteen
     */
    public function setCategoryFourteen($category_fourteen)
    {
        $this->category_fourteen = $category_fourteen;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategoryFifteen()
    {
        return $this->category_fifteen;
    }

    /**
     * @param mixed $category_fifteen
     */
    public function setCategoryFifteen($category_fifteen)
    {
        $this->category_fifteen = $category_fifteen;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategorySixteen()
    {
        return $this->category_sixteen;
    }

    /**
     * @param mixed $category_sixteen
     */
    public function setCategorySixteen($category_sixteen)
    {
        $this->category_sixteen = $category_sixteen;
    }

    /**
     * @return EuroMillionsDrawBreakDownData
     */
    public function getCategorySeventeen()
    {
        return $this->category_seventeen;
    }

    /**
     * @param mixed $category_seventeen
     */
    public function setCategorySeventeen($category_seventeen)
    {
        $this->category_seventeen = $category_seventeen;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    /**
     * @param $cnt_number
     * @param $cnt_lucky
     * @return mixed
     */
    public function getAwardFromCategory($cnt_number, $cnt_lucky)
    {
        return $this->mappingAward($cnt_number, $cnt_lucky);
    }

    private function loadBreakDownData()
    {
        $collection = (empty($this->breakdown[0])) ? $this->breakdown : $this->breakdown[0];

        foreach ($collection as $key => $breakDown) {
//            $nameMethod = 'set' . str_replace("_", "", ucwords($key, '_'));
            //Change to work in Local
            $nameMethod = 'set' . str_replace(" ", "", ucwords(str_replace("_", " ", $key)));
            try {
                if (is_array($breakDown)) {
                    if (($breakDown[1] instanceof Money)) {
                        $money = $breakDown[1];
                    } else {
                        if (!is_numeric($breakDown[1])) {
                            throw new \Exception();
                        }
                        $value = intval(str_replace(',', '', $breakDown[1]));
                        $money = new Money($value, new Currency('EUR'));
                    }
                    $euroMillionsDrawBreakDown = new EuroMillionsDrawBreakDownData();
                    $euroMillionsDrawBreakDown->setName($breakDown[0]);
                    $euroMillionsDrawBreakDown->setLotteryPrize($money);
                    $euroMillionsDrawBreakDown->setWinners($breakDown[2]);
                    $euroMillionsDrawBreakDown->setCategoryName($key);
                    $this->$nameMethod($euroMillionsDrawBreakDown);
                }
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }


    private function mappingAward($cnt_number, $cnt_lucky)
    {
        $name_property = $this->structureOfCombinations()[$cnt_number . $cnt_lucky];
        return $this->$name_property->getLotteryPrize();
    }


    private function structureOfCombinations()
    {
        return [
            52 => 'category_one',
            51 => 'category_two',
            50 => 'category_three',
            42 => 'category_four',
            41 => 'category_five',
            32 => 'category_six',
            40 => 'category_seven',
            22 => 'category_eight',
            31 => 'category_nine',
            30 => 'category_ten',
            12 => 'category_eleven',
            21 => 'category_twelve',
            20 => 'category_thirteen'
        ];
    }

    public function toArray()
    {
        $categories = $this->structureOfCombinations();
        $result = [];
        foreach ($categories as $category) {
            $method = 'get' . StringHelper::fromUnderscoreToCamelCase($category);
            $data = $this->$method()->toArray();
            $data_prefixed = [];
            foreach ($data as $key => $value) {
                $data_prefixed[$category . '_' . $key] = $value;
            }
            $result = array_merge($result, $data_prefixed);
        }
        return $result;
    }



}