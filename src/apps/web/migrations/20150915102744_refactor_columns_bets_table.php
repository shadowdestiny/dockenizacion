<?php namespace EuroMillions\web\migrations;

use Phinx\Migration\AbstractMigration;

class RefactorColumnsBetsTable extends AbstractMigration
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
        $this->execute("
                ALTER TABLE bets DROP FOREIGN KEY FK_FBF0EC9B43FD6CAB;
                DROP INDEX IDX_FBF0EC9B43FD6CAB ON bets;
                ALTER TABLE bets DROP bet_regular_numbers, DROP bet_lucky_numbers, CHANGE playconfig_id play_config_id INT UNSIGNED DEFAULT NULL;
                ALTER TABLE bets ADD CONSTRAINT FK_7C28752B13C82ADE FOREIGN KEY (play_config_id) REFERENCES play_configs (id);
                CREATE INDEX IDX_7C28752B13C82ADE ON bets (play_config_id);
                ALTER TABLE play_configs DROP FOREIGN KEY FK_16AC0F3CA76ED395;
                ALTER TABLE play_configs ADD active TINYINT(1) DEFAULT '1' NOT NULL;
                DROP INDEX idx_16ac0f3ca76ed395 ON play_configs;
                CREATE INDEX IDX_34771F83A76ED395 ON play_configs (user_id);
                ALTER TABLE play_configs ADD CONSTRAINT FK_16AC0F3CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
        ");
    }
}
