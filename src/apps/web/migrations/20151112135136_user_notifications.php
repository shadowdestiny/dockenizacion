<?php

use Phinx\Migration\AbstractMigration;

class UserNotifications extends AbstractMigration
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
        $this->execute("CREATE TABLE users_notifications (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id VARCHAR(255) DEFAULT NULL, notification_id INT UNSIGNED DEFAULT NULL, active TINYINT(1) DEFAULT '1', INDEX IDX_69E5B8DEA76ED395 (user_id), INDEX IDX_69E5B8DEEF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
                        CREATE TABLE notifications (id INT UNSIGNED AUTO_INCREMENT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
                        ALTER TABLE users_notifications ADD CONSTRAINT FK_69E5B8DEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
                        ALTER TABLE users_notifications ADD CONSTRAINT FK_69E5B8DEEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notifications (id);
                      ");
    }
}
