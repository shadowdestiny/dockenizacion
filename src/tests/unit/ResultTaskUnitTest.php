<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

class ResultTaskUnitTest
{

    private $playConfigRepository_double;

    private $euroMillionsDrawRepository_double;

    private $lotteryDrawRepository_double;

    private $lotteriesDataService_double;

    private $betRepository_double;

    private $userRepository_double;

    private $playService_double;

    private $emailService_double;

    private $userService_double;

    private $currencyService_double;

//    protected function getEntityManagerStubExtraMappings()
//    {
//        return [
//            Namespaces::ENTITIES_NS . 'PlayConfig' => $this->playConfigRepository_double,
//            Namespaces::ENTITIES_NS . 'EuroMillionsDraw' => $this->euroMillionsDrawRepository_double,
//            Namespaces::ENTITIES_NS . 'Lottery' => $this->lotteryDrawRepository_double,
//            Namespaces::ENTITIES_NS . 'Bet' => $this->betRepository_double,
//            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
//        ];
//    }
//
//    public function setUp()
//    {
//        $this->lotteriesDataService_double = $this->getServiceDouble('LotteriesDataService');
//        $this->playService_double = $this->getServiceDouble('PlayService');
//        $this->emailService_double = $this->getServiceDouble('EmailService');
//        $this->userService_double = $this->getServiceDouble('UserService');
//        $this->currencyService_double = $this->getServiceDouble('CurrencyService');
//        parent::setUp();
//    }
    

    /**
     * @param string $currency
     * @return User
     */
    private function getUser($currency = 'EUR')
    {
        $user = new User();
        $user->initialize(
            [
                'id' => '9098299B-14AC-4124-8DB0-19571EDABE55',
                'name'     => 'test',
                'surname'  => 'test01',
                'email'    => new Email('raul.mesa@panamedia.net'),
                'password' => new Password('passworD01', new NullPasswordHasher()),
                'validated' => false,
                'wallet' => new Wallet(new Money(5000,new Currency($currency))),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }

    protected function getBreakDownDataDraw()
    {
        return [
            [
                'category_one' => ['5 + 2', '189080000', '0'],
                'category_two' => ['5 + 1', '2939257', '9'],
                'category_three' => ['5 + 0', '8817797', '10'],
                'category_four' => ['4 + 2', '668015', '66'],
                'category_five' => ['4 + 1', '27516', '1.402'],
                'category_six' => ['4 + 0', '13149', '2.934'],
                'category_seven' => ['3 + 2', '6087', '4.527'],
                'category_eight' => ['2 + 2', '1893', '66.973'],
                'category_nine' => ['3 + 1', '1673', '72.488'],
                'category_ten' => ['3 + 0', '1341', '152.009'],
                'category_eleven' => ['1 + 2', '998', '358.960'],
                'category_twelve' => ['2 + 1', '852', '1.138.617'],
                'category_thirteen' => ['2 + 0', '415', '2.390.942'],
            ]
        ];
    }
}