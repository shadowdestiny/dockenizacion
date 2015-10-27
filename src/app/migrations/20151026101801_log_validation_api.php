<?php

use Phinx\Migration\AbstractMigration;

class LogValidationApi extends AbstractMigration
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
        $this->execute('CREATE TABLE log_validation_api (id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                        castillo_bet INT DEFAULT NULL, id_provider INT NOT NULL, status VARCHAR(255) NOT NULL,
                        response LONGTEXT NOT NULL, received DATETIME NOT NULL, INDEX IDX_4B44F776754B05EC (castillo_bet),
                        PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
                        ALTER TABLE log_validation_api ADD CONSTRAINT FK_4B44F776754B05EC
                        FOREIGN KEY (castillo_bet) REFERENCES bets (castillo_bet_id);');
    }
}
