<?php

use Phinx\Migration\AbstractMigration;

class MatcherTable extends AbstractMigration
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
        $this->execute('CREATE TABLE matcher (id INT UNSIGNED AUTO_INCREMENT NOT NULL, matchSide VARCHAR(1) DEFAULT NULL, drawDate DATE NOT NULL, matchStatus VARCHAR(10) DEFAULT NULL, matchID BIGINT DEFAULT NULL, matchDate DATETIME NOT NULL, prize_amount BIGINT DEFAULT NULL, prize_currency_name VARCHAR(255) DEFAULT NULL, providerBetId BIGINT DEFAULT NULL, userId CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_7121252C68D879E3 (providerBetId), INDEX IDX_7121252C64B64DCC (userId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
                        ALTER TABLE matcher ADD CONSTRAINT FK_7121252C68D879E3 FOREIGN KEY (providerBetId) REFERENCES log_validation_api (id_ticket);
                        ALTER TABLE matcher ADD CONSTRAINT FK_7121252C64B64DCC FOREIGN KEY (userId) REFERENCES users (id);');
    }
}
