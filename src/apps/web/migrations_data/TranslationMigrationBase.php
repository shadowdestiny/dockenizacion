<?php
namespace EuroMillions\migrations_data;

use Phinx\Migration\AbstractMigration;

class TranslationMigrationBase extends AbstractMigration
{
    protected function insertTranslationTree()
    {
        foreach ($this->translation_tree as $language => $data) {
            $language_values[] = "('$language')";
            foreach ($data as $key => $value) {
                $translation_values[] = "('$key')";
                $str = "INSERT INTO translation_details"
                    . " (`translation_id`, `lang`, `value`, `language_id`)"
                    . " SELECT t.id, '$language', '$value', l.id"
                    . " FROM translations t, languages l"
                    . " WHERE t.`key` = '$key' AND l.ccode = '$language'";
                $translation_details_insert[] = $str;
            }
        }
        $language_values = implode(',', $language_values);
        $translation_values = implode(',', $translation_values);

        $sql = "INSERT IGNORE INTO languages (`ccode`) VALUES $language_values";
        $this->execute($sql);
        $sql = "INSERT IGNORE INTO translations (`key`) VALUES $translation_values";
        $this->execute($sql);
        $insert = implode(';', $translation_details_insert);
        $this->execute($insert);
    }

}