<?php

use Phinx\Migration\AbstractMigration;

class RenameEmailNotifications extends AbstractMigration
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
        $this->execute('Rename Table notifications TO notifications_type;
                        Rename Table users_notifications TO user_notifications;
                        ALTER TABLE user_notifications DROP FOREIGN KEY FK_69E5B8DEA76ED395;
                        ALTER TABLE user_notifications DROP FOREIGN KEY FK_69E5B8DEEF1A9D84;
                        DROP INDEX idx_69e5b8dea76ed395 ON user_notifications;
                        CREATE INDEX IDX_8E8E1D83A76ED395 ON user_notifications (user_id);
                        DROP INDEX idx_69e5b8deef1a9d84 ON user_notifications;
                        CREATE INDEX IDX_8E8E1D83EF1A9D84 ON user_notifications (notification_id);
                        ALTER TABLE user_notifications ADD CONSTRAINT FK_69E5B8DEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
                        ALTER TABLE user_notifications ADD CONSTRAINT FK_69E5B8DEEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notifications_type (id);
         ');
    }
}
