<?php

use Phinx\Migration\AbstractMigration;

class TcUsersList extends AbstractMigration
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
        $this->execute("CREATE TABLE tc_users_list (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)', trackingCode_id INT UNSIGNED DEFAULT NULL, INDEX IDX_A97819EAB9D8A344 (trackingCode_id), INDEX IDX_A97819EAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE tc_users_list ADD CONSTRAINT FK_A97819EAB9D8A344 FOREIGN KEY (trackingCode_id) REFERENCES trackingCodes (id);
ALTER TABLE tc_users_list ADD CONSTRAINT FK_A97819EAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);");
    }
}
