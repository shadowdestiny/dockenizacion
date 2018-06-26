<?php

use Phinx\Migration\AbstractMigration;

class PlayConfigTransaction extends AbstractMigration
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
        $this->execute('CREATE TABLE playconfig_transaction (id INT UNSIGNED AUTO_INCREMENT NOT NULL, transactionID INT UNSIGNED DEFAULT NULL, playConfig_id INT UNSIGNED DEFAULT NULL, INDEX IDX_3CBA9E0AF99A11DC (transactionID), INDEX IDX_3CBA9E0A43FD6CAB (playConfig_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
                             ALTER TABLE playconfig_transaction ADD CONSTRAINT FK_3CBA9E0AF99A11DC FOREIGN KEY (transactionID) REFERENCES transactions (id);
                             ALTER TABLE playconfig_transaction ADD CONSTRAINT FK_3CBA9E0A43FD6CAB FOREIGN KEY (playConfig_id) REFERENCES play_configs (id);');
    }
}
