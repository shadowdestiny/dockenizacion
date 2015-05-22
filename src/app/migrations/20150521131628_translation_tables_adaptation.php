<?php

use Phinx\Migration\AbstractMigration;

class TranslationTablesAdaptation extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute("ALTER TABLE languages ADD active TINYINT(1) DEFAULT '0' NOT NULL;");
        $this->execute("DROP INDEX ccode ON languages;");
        $this->execute("CREATE UNIQUE INDEX UNIQ_A0D153794EE11504 ON languages (ccode);");
        $this->execute("ALTER TABLE translations CHANGE `key` `key` VARCHAR(255) NOT NULL, CHANGE used used TINYINT(1) DEFAULT '0' NOT NULL;");
        $this->execute("DROP INDEX `key` ON translations;");
        $this->execute("CREATE UNIQUE INDEX UNIQ_C6B7DA878A90ABA9 ON translations (`key`);");
        $this->execute("ALTER TABLE translation_details CHANGE translation_id translation_id INT UNSIGNED DEFAULT NULL, CHANGE value value VARCHAR(255) NOT NULL;");
        $this->execute("ALTER TABLE translation_details ADD CONSTRAINT FK_D32AF2789CAA2B25 FOREIGN KEY (translation_id) REFERENCES translations (translation_id);");
        $this->execute("ALTER TABLE translation_details ADD CONSTRAINT FK_D32AF27882F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id);");
        $this->execute("CREATE INDEX IDX_D32AF2789CAA2B25 ON translation_details (translation_id);");
        $this->execute("CREATE INDEX IDX_D32AF27882F1BAF4 ON translation_details (language_id);");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}