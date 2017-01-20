<?php

use Phinx\Migration\AbstractMigration;

class TcAttributes extends AbstractMigration
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
        $this->execute("CREATE TABLE tc_attributes (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(45) DEFAULT NULL, conditions LONGTEXT DEFAULT NULL, function_name VARCHAR(45) DEFAULT NULL, relationship_table VARCHAR(45) DEFAULT NULL, trackingCode_id INT UNSIGNED DEFAULT NULL, INDEX IDX_13A2318CB9D8A344 (trackingCode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE tc_attributes ADD CONSTRAINT FK_13A2318CB9D8A344 FOREIGN KEY (trackingCode_id) REFERENCES trackingCodes (id);");
    }
}
