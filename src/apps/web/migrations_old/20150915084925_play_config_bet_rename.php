<?php

use Phinx\Migration\AbstractMigration;

class PlayConfigBetRename extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     */
    public function change()
    {
       $this->execute("ALTER TABLE bet rename bets;
                       ALTER TABLE play_config rename play_configs;
                       ALTER TABLE play_configs ADD line_regular_numbers VARCHAR(255) DEFAULT NULL, ADD line_lucky_numbers VARCHAR(255) DEFAULT NULL, DROP play_config_regular_numbers, DROP play_config_lucky_numbers;");
    }
}
