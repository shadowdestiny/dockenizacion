<?php

use Phinx\Migration\AbstractMigration;

class AddEuroMillionsDrawInBet extends AbstractMigration
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
        $this->execute("ALTER TABLE bets ADD euromillions_draw INT UNSIGNED DEFAULT NULL;
                        ALTER TABLE bets ADD CONSTRAINT FK_7C28752BB6F88F5C FOREIGN KEY (euromillions_draw) REFERENCES euromillions_draws (id);
                        CREATE UNIQUE INDEX UNIQ_7C28752BB6F88F5C ON bets (euromillions_draw);
                        ALTER TABLE play_configs ADD play_config_date_end DATE DEFAULT NULL;"
        );
    }
}
