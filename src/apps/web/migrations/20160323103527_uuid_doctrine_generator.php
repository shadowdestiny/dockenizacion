<?php
use Phinx\Migration\AbstractMigration;

class UuidDoctrineGenerator extends AbstractMigration
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
        $this->execute('SET foreign_key_checks = 0;');
        $this->execute("ALTER TABLE play_configs CHANGE user_id user_id CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)';");
        $this->execute("ALTER TABLE users CHANGE id id CHAR(36) NOT NULL COMMENT '(DC2Type:uuid)';");
        $this->execute("ALTER TABLE user_notifications CHANGE user_id user_id CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)';");
        $this->execute('SET foreign_key_checks = 1;');
    }
}
