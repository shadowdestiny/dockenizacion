<?php

use Phinx\Migration\AbstractMigration;

class MatchHistoryEntity extends AbstractMigration
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
        $this->execute('CREATE TABLE matchHistory (id INT UNSIGNED AUTO_INCREMENT NOT NULL, userID VARCHAR(36) DEFAULT NULL, matchTypeID INT DEFAULT NULL, providerBetId BIGINT DEFAULT NULL, drawDate DATE NOT NULL, matchStatus VARCHAR(10) DEFAULT NULL, matchDate DATETIME DEFAULT NULL, lPrize_amount BIGINT DEFAULT NULL, lPrize_currency_name VARCHAR(255) DEFAULT NULL, rPrize_amount BIGINT DEFAULT NULL, rPrize_currency_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
    }
}
