<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\WelcomeEmailTemplate;
use EuroMillions\web\services\email_templates_strategies\NullEmailTemplateDataStrategy;

class WelcomeEmailTemplateUnitTest extends UnitTestBase
{

    /**
     * method loadVarsAsObject
     * when called
     * should returnPropertiesInLoadVarsAsObjectStd
     */
    public function test_loadVarsAsObject_called_returnPropertiesInLoadVarsAsObjectStd()
    {

        $propArray = $this->getLoadVarsAsArray();
        $obj = new \stdClass();
        $obj->user_name = 'testing';
        $obj->howToPlay = '/help';
        $obj->numbers = '/numbers';
        $obj->subscribe = '/numbers';
        $obj->faq = '/faq';
        $obj->contact = 'mailto:support@euromillions.com';
        $obj->breakdown = [['test' => 'test','test2' => 'test2']];

        $expected = $obj;
        $sut = new WelcomeEmailTemplate(new EmailTemplate(), new NullEmailTemplateDataStrategy());
        $actual = $sut->loadVarsAsObject($propArray['vars']);
        $this->assertEquals($expected,$actual);
    }



    private function getLoadVarsAsArray()
    {

        return [
            'template' => 'welcome',
            'subject' => 'Welcome to Euromillions.com',
            'vars' => [
                [
                    'name' => 'user_name',
                    'content' => 'testing'
                ],
                [
                    'name' => 'howToPlay',
                    'content' => '/help'
                ],
                [
                    'name' => 'numbers',
                    'content' => '/numbers'
                ],
                [
                    'name' => 'subscribe',
                    'content' => '/numbers'
                ],
                [
                    'name' => 'faq',
                    'content' => '/faq'
                ],
                [
                    'name' => 'contact',
                    'content' => 'mailto:support@euromillions.com'
                ],
                [
                    'name' => 'breakdown',
                    'content' => [['test' => 'test', 'test2' => 'test2']]

                ]
            ]
        ];
    }


}