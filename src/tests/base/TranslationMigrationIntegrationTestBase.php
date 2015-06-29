<?php


namespace tests\base;

use Phalcon\Di;
use Phalcon\Loader;
use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

abstract class TranslationMigrationIntegrationTestBase extends DatabaseIntegrationTestBase
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
            $lang_id = $this->checkLanguageIsInDb($conn, $language);
            foreach ($data as $key => $value) {
                $translation_id = $this->checkTranslationIsInDb($conn, $key);
                $this->checkTranslationDetail($conn, $language, $lang_id, $translation_id, $value, $key);
            }
        }
        $sut->up();
        $this->checkNoRepetitionsInLanguage($conn);
        $this->checkNoRepetitionsInTranslation($conn);
    }

    /**
     * @param $conn
     * @param $language
     * @return mixed
     */
    protected function checkLanguageIsInDb($conn, $language)
    {
        $lang_result = $conn->query("SELECT id FROM languages WHERE ccode = '$language'");
        $lang_id = $lang_result->fetchColumn(0);
        $this->assertNotEmpty($lang_id, "Language $language doesn't exist in the database");
        $this->assertGreaterThan(0, $lang_id, "Language id should be an integer greater than 0");
        return $lang_id;
    }

    /**
     * @param $conn
     * @param $key
     * @return mixed
     */
    protected function checkTranslationIsInDb($conn, $key)
    {
        $translation_result = $conn->query("SELECT `translation_id` FROM translations WHERE `key`='$key'");
        $translation_id = $translation_result->fetchColumn(0);
        $this->assertNotEmpty($translation_id, "Traslation for key $key doesn't exist in the database");
        $this->assertGreaterThan(0, $translation_id, "Translation id should be an integer greater than 0");
        return $translation_id;
    }

    /**
     * @param $conn
     * @param $language
     * @param $lang_id
     * @param $translation_id
     * @param $value
     * @param $key
     */
    protected function checkTranslationDetail($conn, $language, $lang_id, $translation_id, $value, $key)
    {
        $translation_details_result = $conn->query(
            "SELECT `value` FROM translation_details WHERE lang='$language' AND language_id='$lang_id' AND translation_id = $translation_id"
        );
        $translation_value = $translation_details_result->fetchColumn(0);
        $this->assertEquals($value, $translation_value, "The translation value '$translation_value' doesn't correspond to the expected value '$value' for the key '$key' and the language '$language'");
    }

    /**
     * @param $conn
     */
    protected function checkNoRepetitionsInLanguage($conn)
    {
        $language_repetitions = $conn->query("SELECT id FROM languages GROUP BY ccode HAVING COUNT(ccode) > 1 ");
        $language_repetitions_result = $language_repetitions->fetch();
        $this->assertEmpty($language_repetitions_result);
    }

    /**
     * @param $conn
     */
    protected function checkNoRepetitionsInTranslation($conn)
    {
        $translation_repetitions = $conn->query("SELECT translation_id FROM translations GROUP BY `key` HAVING COUNT(`key`) > 1 ");
        $translation_repetitions_result = $translation_repetitions->fetch();
        $this->assertEmpty($translation_repetitions_result);
    }
}