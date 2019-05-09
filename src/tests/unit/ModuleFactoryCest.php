<?php

use Phalcon\Di;
use Phalcon\Mvc\Application;
use EuroMillions\web\services\factories\ModuleFactory;
use EuroMillions\shared\config\bootstrap\modules\WebModule;
use EuroMillions\shared\config\bootstrap\modules\AdminModule;
use EuroMillions\shared\config\bootstrap\modules\MegaSenaModule;
use EuroMillions\shared\config\bootstrap\modules\EuroJackpotModule;
use EuroMillions\shared\config\bootstrap\modules\MegaMillionsModule;
use EuroMillions\shared\config\bootstrap\modules\SuperEnalottoModule;

class ModuleFactoryCest
{
    /**
     * @var Phalcon\Di
     */
    protected $di;

    /**
     * @var Phalcon\Mvc\Application
     */
    protected $application;

    /**
     * @var string
     */
    protected $appPath;

    /**
     * @var string
     */
    protected $assetsPath;

    public function _before(\UnitTester $I)
    {
        $this->di = Di::getDefault();
        $this->application = new Application($this->di);
        $public_path = __DIR__;
        $this->appPath = $public_path . '/../../apps/';
        $this->assetsPath = $this->appPath . 'web/assets/';
    }

    /**
     * method create
     * when isWeb
     * should returnInstanceOfWebModule
     * @param UnitTester $I
     * @group module-factory
     */
    public function test_create_isWeb_returnInstanceOfWebModule(UnitTester $I)
    {
        $module = ModuleFactory::create('web', $this->application, $this->di, $this->appPath, $this->assetsPath, $this->di);

        $I->assertTrue($module instanceof WebModule);
    }

    /**
     * method create
     * when isAdmin
     * should returnInstanceOfAdminModule
     * @param UnitTester $I
     * @group module-factory
     */
    public function test_create_isAdmin_returnInstanceOfAdminModule(UnitTester $I)
    {
        $module = ModuleFactory::create('admin', $this->application, $this->di, $this->appPath, $this->assetsPath, $this->di);

        $I->assertTrue($module instanceof AdminModule);
    }

    /**
     * method create
     * when isMegaMillions
     * should returnInstanceOfMegaMillionsModule
     * @param UnitTester $I
     * @group module-factory
     */
    public function test_create_isMegaMillions_returnInstanceOfMegaMillionsModule(UnitTester $I)
    {
        $module = ModuleFactory::create('megamillions', $this->application, $this->di, $this->appPath, $this->assetsPath, $this->di);

        $I->assertTrue($module instanceof MegaMillionsModule);
    }

    /**
     * method create
     * when isEuroJackpot
     * should returnInstanceOfEuroJackpotModule
     * @param UnitTester $I
     * @group module-factory
     */
    public function test_create_isEuroJackpot_returnInstanceOfEuroJackpotModule(UnitTester $I)
    {
        $module = ModuleFactory::create('eurojackpot', $this->application, $this->di, $this->appPath, $this->assetsPath, $this->di);

        $I->assertTrue($module instanceof EuroJackpotModule);
    }

    /**
     * method create
     * when isMegaSena
     * should returnInstanceOfMegaSenaModule
     * @param UnitTester $I
     * @group module-factory
     */
    public function test_create_isMegaSena_returnInstanceOfMegaSenaModule(UnitTester $I)
    {
        $module = ModuleFactory::create('megasena', $this->application, $this->di, $this->appPath, $this->assetsPath, $this->di);

        $I->assertTrue($module instanceof MegaSenaModule);
    }

    /**
     * method create
     * when isSuperEnalotto
     * should returnInstanceOfSuperEnalottoModule
     * @param UnitTester $I
     * @group module-factory
     */
    public function test_create_isSuperEnalotto_returnInstanceOfSuperEnalottoModule(UnitTester $I)
    {
        $module = ModuleFactory::create('superenalotto', $this->application, $this->di, $this->appPath, $this->assetsPath, $this->di);

        $I->assertTrue($module instanceof SuperEnalottoModule);
    }
}
