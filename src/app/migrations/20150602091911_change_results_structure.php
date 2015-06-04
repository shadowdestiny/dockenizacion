<?php

use Phinx\Migration\AbstractMigration;

class ChangeResultsStructure extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
     * public function change()
     * {
     * }
     */

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute("ALTER TABLE lotteries ADD frequency VARCHAR(255) NOT NULL, ADD draw_time VARCHAR(255) NOT NULL;");
        $this->execute("ALTER TABLE lottery_draws ADD result_id INT UNSIGNED DEFAULT NULL;");
        $this->execute("ALTER TABLE lottery_draws ADD CONSTRAINT FK_41E8782B7A7B643 FOREIGN KEY (result_id) REFERENCES lottery_results (id);");
        $this->execute("CREATE UNIQUE INDEX UNIQ_41E8782B7A7B643 ON lottery_draws (result_id);");
        $this->execute("DROP INDEX draw_id ON lottery_results;");
        $this->execute("ALTER TABLE lottery_results ADD lottery VARCHAR(255) NOT NULL, ADD regular_numbers VARCHAR(255) DEFAULT NULL, ADD lucky_numbers VARCHAR(255) DEFAULT NULL, DROP draw_id, DROP type, DROP pos, DROP number;");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}