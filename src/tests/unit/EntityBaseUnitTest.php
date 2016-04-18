<?php
namespace EuroMillions\tests\unit;

use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\EntityBase;
use EuroMillions\web\entities\Language;
use EuroMillions\web\entities\Lottery;
use EuroMillions\tests\base\UnitTestBase;

class EntityBaseUnitTest extends UnitTestBase
{
    /** @var  EntityBase */
    protected $sut;

    public function setUp()
    {
        parent::setUp();
        $this->sut = new Language();
    }

    public function test_initialize_calledWithArgument_setProperties()
    {
        $this->sut->initialize(['id' => 1, 'ccode' => 'es', 'active' => 3]);
        $this->assertEquals('1es3', $this->sut->getId() . $this->sut->getCcode() . $this->sut->getActive());
    }

    public function test_initialize_calledWithWrongPropertyName_throw()
    {
        $bad_name = 'badproperty';
        $this->setExpectedException($this->getExceptionToArgument('BadEntityInitializationException'), 'Bad property name: "' . $bad_name . '"');

        $this->sut->initialize(['ccode' => 'es', $bad_name => 'anyway']);
    }

    public function test_toValueObject_called_transformEntityToValueObject()
    {
        $this->sut->initialize(['ccode' => 'en', 'active' => 1, 'defaultLocale' => 'en_US']);
        $expected = new \stdClass();
        $expected->ccode = 'en';
        $expected->active = 1;
        $expected->id = null;
        $expected->defaultLocale = 'en_US';
        $actual = $this->sut->toValueObject();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method initialize
     * when calledWithFieldThatHasUnderscore
     * should setProperties
     */
    public function test_initialize_calledWithFieldThatHasUnderscore_setProperties()
    {
        $sut = new Lottery();
        $sut->initialize([
                'id'        => 1,
                'name'      => 'EuroMillions',
                'active'    => 1,
                'frequency' => 'frequency',
                'draw_time' => 'time'
            ]
        );
        $this->assertEquals('time', $sut->getDrawTime());
    }

    /**
     * method toValueObject
     * when calledWithAnEntityWithRelations
     * should notReturnRelationProperties
     */
    public function test_toValueObject_calledWithAnEntityWithRelations_notReturnRelationProperties()
    {
        $user = UserMother::anAlreadyRegisteredUser()->build();
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $user->getUserNotification(), 'Failsafe');

        $expected = new \stdClass();
        $expected->id = $user->getId();
        $expected->name = $user->getName();
        $expected->surname = $user->getSurname();
        $expected->email = $user->getEmail();
        $expected->rememberToken = $user->getRememberToken();
        $expected->wallet = $user->getWallet();
        $expected->country = $user->getCountry();
        $expected->validated = $user->getValidated();
        $expected->validationToken = $user->getValidationToken();
        $expected->street = $user->getStreet();
        $expected->zip = $user->getZip();
        $expected->city = $user->getCity();
        $expected->password = $user->getPassword();
        $expected->jackpotReminder = null;
        $expected->bankAccount = null;
        $expected->bankName = null;
        $expected->bankSwift = null;

        $this->assertEquals($expected, $user->toValueObject());
    }

    /**
     * method toArray
     * when called
     * should returnValueObjectsExpandedInProperties
     */
    public function test_toArray_called_returnValueObjectsExpandedInProperties()
    {
        $user = UserMother::anAlreadyRegisteredUser()->build();

        $expected = [
            'id'                            => '9098299B-14AC-4124-8DB0-19571EDABE55',
            'name'                          => $user->getName(),
            'surname'                       => $user->getSurname(),
            'password'                      => $user->getPassword()->toNative(),
            'email'                         => $user->getEmail()->toNative(),
            'remember_token'                 => null,
            'wallet_uploaded_amount'        => $user->getWallet()->getUploaded()->getAmount(),
            'wallet_uploaded_currency_name' => $user->getWallet()->getUploaded()->getCurrency()->getName(),
            'wallet_winnings_amount'        => $user->getWallet()->getWinnings()->getAmount(),
            'wallet_winnings_currency_name' => $user->getWallet()->getWinnings()->getCurrency()->getName(),
            'country'                       => $user->getCountry(),
            'validated'                     => $user->getValidated(),
            'validation_token'               => $user->getValidationToken(),
            'street'                        => $user->getStreet(),
            'zip'                           => $user->getZip(),
            'city'                          => $user->getCity(),
            'jackpot_reminder'               => null,
            'bank_name'                      => null,
            'bank_account'                   => null,
            'bank_swift'                     => null
        ];
        $this->assertEquals($expected, $user->toArray());
    }

}