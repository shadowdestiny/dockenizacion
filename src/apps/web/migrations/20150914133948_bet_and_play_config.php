<?php namespace EuroMillions\web\migrations;

use Phinx\Migration\AbstractMigration;

class BetAndPlayConfig extends AbstractMigration
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

        $this->execute("CREATE TABLE bet (id INT UNSIGNED AUTO_INCREMENT NOT NULL, bet_regular_numbers VARCHAR(255) DEFAULT NULL, bet_lucky_numbers VARCHAR(255) DEFAULT NULL, playConfig_id INT UNSIGNED DEFAULT NULL, INDEX IDX_FBF0EC9B43FD6CAB (playConfig_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
                        CREATE TABLE play_config (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id VARCHAR(255) DEFAULT NULL, play_config_regular_numbers VARCHAR(255) DEFAULT NULL, play_config_lucky_numbers VARCHAR(255) DEFAULT NULL, INDEX IDX_16AC0F3CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
                        ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9B43FD6CAB FOREIGN KEY (playConfig_id) REFERENCES play_config (id);
                        ALTER TABLE play_config ADD CONSTRAINT FK_16AC0F3CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);");

    }
}
