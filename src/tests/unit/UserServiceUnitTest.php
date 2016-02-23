<?php
namespace tests\unit;

use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\Notification;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\ContactFormInfo;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\web\vo\ExpiryDate;
use EuroMillions\web\vo\NotificationType;
use EuroMillions\web\vo\Password;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\UnitTestBase;
use tests\helpers\mothers\UserMother;


class UserServiceUnitTest extends UnitTestBase
{
    private $userRepository_double;
    private $currencyService_double;
    private $storageStrategy_double;
    private $emailService_double;
    private $paymentProviderService_double;
    private $paymentMethodRepository_double;
    private $playRepository_double;
    private $userNotificationsRepository_double;
    private $notificationsRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
            Namespaces::ENTITIES_NS . 'PaymentMethod' => $this->paymentMethodRepository_double,
            Namespaces::ENTITIES_NS . 'PlayConfig' => $this->playRepository_double,
            Namespaces::ENTITIES_NS . 'UserNotifications' => $this->userNotificationsRepository_double,
            Namespaces::ENTITIES_NS . 'Notification' => $this->notificationsRepository_double,
        ];
    }

    public function setUp()
    {
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        $this->currencyService_double = $this->getServiceDouble('CurrencyService');
        $this->storageStrategy_double = $this->getInterfaceWebDouble('IUsersPreferencesStorageStrategy');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->paymentProviderService_double = $this->getServiceDouble('PaymentProviderService');
        $this->paymentMethodRepository_double = $this->getRepositoryDouble('PaymentMethodRepository');
        $this->playRepository_double = $this->getRepositoryDouble('PlayConfigRepository');
        $this->userNotificationsRepository_double = $this->getRepositoryDouble('UserNotificationsRepository');
        $this->notificationsRepository_double = $this->getRepositoryDouble('NotificationRepository');
        parent::setUp();
    }

    /**
     * method contactRequest
     * when called
     * should returnServiceActionResultTrue
     */
    public function test_contactRequest_called_returnServiceActionResultTrue()
    {
        $sut = $this->getSut();
        $actual = $sut->contactRequest(
            new ContactFormInfo(new Email('raul.mesa@panamedia.net'),
            'Raul Mesa Ros',
            'I have a problem',
            'Playing the game'
            )
        );
        $this->assertInstanceOf($this->getResultObject('ActionResult'), $actual);
        $this->assertTrue($actual->success());
    }

    /**
     * method getBalance
     * when called
     * should returnBalanceByUser
     */
    public function test_getBalance_called_returnBalanceByUser()
    {
        $user = $this->getUser();
        $this->userRepository_double->find(Argument::any())->willReturn($user);
        $this->currencyService_double->toString($user->getBalance(), 'es_ES')->willReturn(new MoneyFormatter());
        $sut = $this->getSut();
        $actual = $sut->getBalance($user->getId(), 'es_ES');
        $this->assertInstanceOf('antonienko\MoneyFormatter\MoneyFormatter',$actual);
    }


    /**
     * method getMyPlays
     * when called
     * should returnServiceActionResultTrueWithProperData
     */
    public function test_getMyPlays_called_returnServiceActionResultTrueWithProperData()
    {
        $playConfig = $this->getPlayConfig();
        $expected = new ActionResult(true,$playConfig);
        $sut = $this->getSut();
        $userId = new UserId('9098299B-14AC-4124-8DB0-19571EDABE55');
        $this->playRepository_double->getActivePlayConfigsByUser($userId)->willReturn($playConfig);
        $actual = $sut->getMyActivePlays($userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getMyPlays
     * when calledWithUserIDValid
     * should returnServiceActionResultFalseWithEmtpyValue
     */
    public function test_getMyPlays_calledWithUserIDValid_returnServiceActionResultFalseWithEmtpyValue()
    {
        $expected = new ActionResult(false,'You don\'t have games');
        $sut = $this->getSut();
        $userId = new UserId('9098299B-14AC-4124-8DB0-19571EDABE55');
        $this->playRepository_double->getActivePlayConfigsByUser($userId)->willReturn(null);
        $actual = $sut->getMyActivePlays($userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method updateUserData
     * when called
     * should returnServiceActionResultTrue
     */
    public function test_updateUserData_called_returnServiceActionResultTrue()
    {
        $expected = new ActionResult(true,'Your data was update');
        $credentials = [
            'name'             => 'Raul',
            'surname'          => 'Mesa',
            'email'            => 'rmrbest@gmail.com',
            'password'         => 'passWord01',
            'confirm_password' => 'passWord01',
            'country'          => 'Spain',
            'street'           => 'Tres',
            'city'             => 'Barcelona',
            'zip'              => '08830',
            'phone_number'     => '626966592'

        ];
        $user = $this->getUser();
        $this->userRepository_double->getByEmail($credentials['email'])->willReturn($user);
        $this->userRepository_double->add(Argument::any())->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::any())->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->updateUserData($credentials);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method updateUserDate
     * when called
     * should throrExceptionAndReturnServiceActionResultFalse
     */
    public function test_updateUserDate_called_throrExceptionAndReturnServiceActionResultFalse()
    {
        $expected = new ActionResult(false,'Sorry, try it later');
        $credentials = [
            'name'             => 'Raul',
            'surname'          => 'Mesa',
            'email'            => 'rmrbest@gmail.com',
            'password'         => 'passWord01',
            'confirm_password' => 'passWord01',
            'country'          => 'Spain',
            'street'           => 'Tres',
            'city'             => 'Barcelona',
            'zip'              => '08830',
            'phone_number'     => '626966592'
        ];

        $user = $this->getUser();
        $this->userRepository_double->getByEmail($credentials['email'])->willReturn($user);
        $this->userRepository_double->add(Argument::any())->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::any())->willThrow('Exception');
        $sut = $this->getSut();
        $actual = $sut->updateUserData($credentials);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getAllUsersWithJackpotReminder
     * when called
     * should returnServiceActionResultTrueWithProperData
     */
    public function test_getAllUsersWithJackpotReminder_called_returnServiceActionResultTrueWithProperData()
    {
        $user = [$this->getUser()];
        $expected = new ActionResult(true,$user);
        $this->userRepository_double->getUsersWithJackpotReminder()->willReturn($user);
        $sut = $this->getSut();
        $actual = $sut->getAllUsersWithJackpotReminder();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getAllUsersWithJackpotReminder
     * when called
     * should returnServiceActionResultFalse
     */
    public function test_getAllUsersWithJackpotReminder_called_returnServiceActionResultFalse()
    {
        $expected = new ActionResult(false);
        $this->userRepository_double->getUsersWithJackpotReminder()->willReturn(null);
        $sut = $this->getSut();
        $actual = $sut->getAllUsersWithJackpotReminder();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getActiveNotificationsByUser
     * when calledWithValidUser
     * should returnActionResultTrueWithNotificationCollection
     */
    public function test_getActiveNotificationsByUser_calledWithValidUser_returnActionResultTrueWithNotificationCollection()
    {
        $expected = true;
        $actual = $this->exerciseGetNotifications($expected);
        $this->assertEquals($expected,$actual->success());
    }

    /**
     * method getActiveNotificationsByUser
     * when calledAndEmptyCollection
     * should returnActionResultFalse
     */
    public function test_getActiveNotificationsByUser_calledAndEmptyCollection_returnActionResultFalse()
    {
        $expected = false;
        $actual = $this->exerciseGetNotifications($expected);
        $this->assertEquals($expected,$actual->success());
    }

    /**
     * method updateEmailNotification
     * when called
     * should updateNotificationByUserAndRetunrnActionResultTrue
     */
    public function test_updateEmailNotification_called_updateNotificationByUserAndRetunrnActionResultTrue()
    {
        $expected = true;
        $user = $this->getUser();
        $notificationType = new NotificationType(4,true);
        $notification = $this->getNotifications()[0];
        $active = true;
        $user_notification = $this->getUserNoticiation();
        $this->notificationsRepository_double->findBy(['notification_type' => $notificationType->getType()])->willReturn($notification);
        $this->userNotificationsRepository_double->findOneBy(['user' => $user, 'notification' => $notification])->willReturn($user_notification);
        $this->userNotificationsRepository_double->add($user_notification)->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user_notification)->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->updateEmailNotification($notificationType,$user,$active);
        $this->assertEquals($expected,$actual->success());
    }


    /**
     * method getActiveNotificationsTypeJackpot
     * when called
     * should returnActionResultWithCollection
     */
    public function test_getActiveNotificationsTypeJackpot_called_returnActionResultWithCollection()
    {
        $sut = $this->getSut();
        $this->userNotificationsRepository_double->findBy(['active' => true, 'notification' => 1])->willReturn(true);
        $actual = $sut->getActiveNotificationsTypeJAckpot();
        $this->assertTrue($actual->success());
    }

    /**
     * method initUserNotifications
     * when called
     * should createUserNotificationsDefaultAndReturnActionResultTrue
     */
    public function test_initUserNotifications_called_createUserNotificationsDefaultAndReturnActionResultTrue()
    {
        $expected = new ActionResult(true);
        $userId = new UserId('9098299B-14AC-4124-8DB0-19571EDABE55');

        $this->userRepository_double->find($userId->id())->willReturn(true);
        $this->notificationsRepository_double->findAll()->willReturn($this->getNotifications());
        $this->userNotificationsRepository_double->add(Argument::type('EuroMillions\web\entities\UserNotifications'))->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::type('EuroMillions\web\entities\UserNotifications'))->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->initUserNotifications($userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method initUserNotifications
     * when called
     * should throwExceptionAndReturnActionResultFalse
     */
    public function test_initUserNotifications_called_throwExceptionAndReturnActionResultFalse()
    {
        $expected = new ActionResult(false);
        list($expected, $userId, $entityManager_stub) = $this->exerciseUserNotifications($expected);
        $entityManager_stub->flush(Argument::type('EuroMillions\web\entities\UserNotifications'))->willThrow('Exception');
        $sut = $this->getSut();
        $actual = $sut->initUserNotifications($userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getActiveNotificationsByType
     * when called
     * should returnActionResultTrueWithCollection
     */
    public function test_getActiveNotificationsByType_called_returnActionResultTrueWithCollection()
    {
        $expected = new ActionResult(true,$this->getNotifications());
        $actual = $this->exerciseNotifications($this->getNotifications());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getActiveNotificationsByType
     * when called
     * should returnActionResultFalse
     */
    public function test_getActiveNotificationsByType_called_returnActionResultFalse()
    {
        $expected = new ActionResult(false);
        $actual = $this->exerciseNotifications(false);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method updateCurrency
     * when called
     * should updateNewCurrencyInUserEntity
     */
    public function test_updateCurrency_called_updateNewCurrencyInUserEntity()
    {
        $currency = new Currency('USD');
        $expected = new ActionResult(true, $currency);
        $user = $this->getUser();
        $this->userRepository_double->add(Argument::type('EuroMillions\web\entities\User'))->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::type('EuroMillions\web\entities\User'))->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->updateCurrency($user, $currency);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method chargeFeeFromWallet
     * when calledWithProperData
     * should incrementValueWithChargeValueConfiguredInSystem
     */
    public function test_chargeFeeFromWallet_calledWithProperData_incrementValueWithChargeValueConfiguredInSystem()
    {
        $fee = new Money(35, new Currency('EUR'));
        $fee_limit = new Money(12000, new Currency('EUR'));
        $amount = new Money(1000, new Currency('EUR'));
        $expected = new ActionResult(true,$amount->add($fee));
        $sut = $this->getSut();
        $actual = $sut->chargeFeeFromWallet($amount, $fee_limit, $fee );
        $this->assertEquals($expected, $actual);
    }

    /**
     * method userWonAbove
     * when called
     * should updateUserRowAndReturnValueWon
     */
    public function test_userWonAbove_called_updateUserRowAndReturnValueWon()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $money = new Money(300000, new Currency('EUR'));
        $this->userRepository_double->add($user)->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::type('EuroMillions\web\entities\User'))->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->userWonAbove($user, $money);
        $this->assertEquals($money,$actual->getValues());
    }

    /**
     * method userWonAbove
     * when calledWithAmountLessLimit
     * should returnActionResultFalseWith0Money
     */
    public function test_userWonAbove_calledWithAmountLessLimit_returnActionResultFalseWith0Money()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $expected = new ActionResult(false, new Money(0,$user->getUserCurrency()));
        $money = new Money(100000, new Currency('EUR'));
        $sut = $this->getSut();
        $actual = $sut->userWonAbove($user, $money);
        $this->assertEquals($expected,$actual);
    }


    private function getNotifications()
    {
        $notification = new Notification();
        $notification->initialize([
            'id' => 1,
            'description' => 'Test description'
        ]);

        return [$notification];
    }

    private function getPlayConfig()
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));

        $playConfig = new PlayConfig();
        $playConfig->setUser($this->getUser());
        $playConfig->setLine($euroMillionsLine);
        $playConfig->setActive(true);
        $playConfig->setDrawDays(2);

        return $playConfig;
    }


    /**
     * @param string $currency
     * @return User
     */
    private function getUser($currency = 'EUR')
    {
        $user = new User();
        $user->initialize(
            [
                'id' => new UserId('9098299B-14AC-4124-8DB0-19571EDABE55'),
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

    private function getUserNoticiation()
    {

        $user_notification = new UserNotifications();
        $notificationType = new NotificationType(4,true);
        $user_notification->initialize(
            [
                'id' => 4,
                'user'     => '03bef482-89eb-11e5-ad54-0242ac110002',
                'active'  => 1,
                'notification' => $notificationType
            ]
        );

        return $user_notification;
    }

    /**
     * @return UserService
     */
    protected function getSut()
    {
        $sut = $this->getDomainServiceFactory()->getUserService($this->currencyService_double->reveal(),
                                                                $this->emailService_double->reveal(),
                                                                $this->paymentProviderService_double->reveal());
        return $sut;
    }

    /**
     * @param $payment_provider_result
     * @param $expected
     */
    protected function exerciseRecharge($payment_provider_result, $expected)
    {
        $user = $this->getUser();
        $paymentMethod = $this->getInterfaceWebDouble('ICardPaymentProvider')->reveal();
        $amount = new Money(5000, new Currency('EUR'));
        $this->paymentProviderService_double->charge($paymentMethod, $amount)->willReturn($payment_provider_result);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->shouldNotBeCalled();
        $sut = $this->getSut();
        $actual = $sut->recharge($user, $paymentMethod, $amount);
        $this->assertEquals($expected, $actual->success());
    }

    /**
     * @param $expected
     * @param array $return
     * @return ActionResult
     */
    protected function exerciseGetPaymentMethod($expected,$return = [], $userId = '9098299B-14AC-4124-8DB0-19571EDABE55')
    {
        $userId = new UserId($userId);
        $user = $this->getUser();
        $this->userRepository_double->find($userId)->willReturn($user);
        $this->paymentMethodRepository_double->getPaymentMethodsByUser($user)->willReturn($return);
        $actual = $this->getSut()->getPaymentMethods($userId);
        $this->assertEquals($expected,$actual);
    }

    protected function getRegularNumbers(array $numbers)
    {
        $result = [];
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsRegularNumber($number);
        }
        return $result;
    }
    protected function getLuckyNumbers(array $numbers)
    {
        $result = [];
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsLuckyNumber($number);
        }
        return $result;
    }

    /**
     * @return mixed
     */
    private function exerciseGetNotifications($expected)
    {
        $user = $this->getUser();
        $this->userNotificationsRepository_double->findBy(['user' => $user])->willReturn($expected);
        $sut = $this->getSut();
        return $sut->getActiveNotificationsByUser($user);
    }

    /**
     * @return array
     */
    private function exerciseUserNotifications($expected)
    {
        $userId = new UserId('9098299B-14AC-4124-8DB0-19571EDABE55');
        $this->userRepository_double->find($userId->id())->willReturn(true);
        $this->notificationsRepository_double->findAll()->willReturn($this->getNotifications());
        $this->userNotificationsRepository_double->add(Argument::any())->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        return array($expected, $userId, $entityManager_stub);
    }

    /**
     * @return mixed
     */
    private function exerciseNotifications($return)
    {
        $this->userNotificationsRepository_double->findBy(['active'       => true,
                                                           'notification' => NotificationType::NOTIFICATION_LAST_DRAW

        ])->willReturn($return);
        $sut = $this->getSut();
        $actual = $sut->getActiveNotificationsByType(NotificationType::NOTIFICATION_LAST_DRAW);
        return $actual;
    }

}