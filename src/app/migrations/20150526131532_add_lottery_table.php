<?php

use Phinx\Migration\AbstractMigration;

class AddLotteryTable extends AbstractMigration
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
        $this->execute("CREATE TABLE lotteries (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT '0' NOT NULL, UNIQUE INDEX UNIQ_DB65B0075E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;");
        $this->execute("ALTER TABLE lottery_draws ADD lottery_id INT UNSIGNED DEFAULT NULL, CHANGE jackpot jackpot BIGINT NOT NULL, CHANGE big_winner big_winner TINYINT(1) NOT NULL, CHANGE published published TINYINT(1) NOT NULL;");
        $this->execute("ALTER TABLE lottery_draws ADD CONSTRAINT FK_41E8782BCFAA77DD FOREIGN KEY (lottery_id) REFERENCES lotteries (id);");
        $this->execute("CREATE UNIQUE INDEX UNIQ_41E8782B38C98BF ON lottery_draws (draw_date);");
        $this->execute("CREATE INDEX IDX_41E8782BCFAA77DD ON lottery_draws (lottery_id);");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}