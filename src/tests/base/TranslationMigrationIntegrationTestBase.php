<?php


namespace tests\base;

use Phalcon\Di;
use Phalcon\Loader;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

abstract class TranslationMigrationIntegrationTestBase extends IntegrationTestBase
{
    protected function getFixtures()
    {
        return [
            'languages',
            'translations',
            'translation_details'
        ];
    }

    protected function checkTranslationTree($migrationClassName, $migrationFileName)
    {
        $loader = new Loader();
        $loader->registerClasses([$migrationClassName => APP_PATH.'migrations_data/'.$migrationFileName.'.php']);
        $loader->register();

        $dbname = DI::getDefault()->get('config')->database->dbname;

        /** @var AbstractMigration $sut */
        $sut = new $migrationClassName('1');
        $pdo_adapter = new MysqlAdapter(['connection' => $this->getPDO(), 'name' => $dbname]);

        $sut->setAdapter($pdo_adapter);
        $sut->up();

        $conn = $this->getPDO();
        foreach($sut->translation_tree as $language => $data) {
            $lang_result = $conn->query("SELECT id FROM languages WHERE ccode = '$language'");
            $lang_id = $lang_result->fetchColumn(0);
            $this->assertNotEmpty($lang_id, "Language $language doesn't exist in the database");
            $this->assertGreaterThan(0, $lang_id, "Language id should be an integer greater than 0");
            foreach ($data as $key => $value) {
                $translation_result = $conn->query("SELECT `translation_id` FROM translations WHERE `key`='$key'");
                $translation_id = $translation_result->fetchColumn(0);
                $this->assertNotEmpty($translation_id, "Traslation for key $key doesn't exist in the database");
                $this->assertGreaterThan(0, $translation_id, "Translation id should be an integer greater than 0");

                $translation_details_result = $conn->query(
                    "SELECT `value` FROM translation_details WHERE lang='$language' AND language_id='$lang_id' AND translation_id = $translation_id"
                );
                $translation_value = $translation_details_result->fetchColumn(0);
                $this->assertEquals($value, $translation_value, "The translation value '$translation_value' doesn't correspond to the expected value '$value' for the key '$key' and the language '$language'");
            }
        }
    }
}