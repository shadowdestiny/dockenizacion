<?php

use Phinx\Migration\AbstractMigration;

class RelationShipTranslationWithCategory extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->execute('ALTER TABLE translations ADD translationCategory_id INT UNSIGNED DEFAULT NULL;
                        ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA878D19538D FOREIGN KEY (translationCategory_id) REFERENCES translation_categories (id);
                        CREATE INDEX IDX_C6B7DA878D19538D ON translations (translationCategory_id);
        ');
    }
}
