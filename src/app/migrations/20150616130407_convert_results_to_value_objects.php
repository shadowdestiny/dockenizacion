<?php

use Phinx\Migration\AbstractMigration;

class ConvertResultsToValueObjects extends AbstractMigration
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
        $this->execute("CREATE TABLE euromillions_draws (draw_id INT UNSIGNED AUTO_INCREMENT NOT NULL, lottery_id INT UNSIGNED DEFAULT NULL, draw_date DATE NOT NULL, jackpot BIGINT NOT NULL, result_regular_numbers VARCHAR(255) NOT NULL, result_lucky_numbers VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_79652A5B38C98BF (draw_date), INDEX IDX_79652A5BCFAA77DD (lottery_id), PRIMARY KEY(draw_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;");
        $this->execute("ALTER TABLE euromillions_draws ADD CONSTRAINT FK_79652A5BCFAA77DD FOREIGN KEY (lottery_id) REFERENCES lotteries (id);");
        $this->execute("ALTER TABLE euromillions_draws CHANGE result_regular_numbers result_regular_numbers VARCHAR(255) DEFAULT NULL, CHANGE result_lucky_numbers result_lucky_numbers VARCHAR(255) DEFAULT NULL;");
        $this->execute("DROP TABLE lottery_draws");
        $this->execute("DROP TABLE lottery_results");
    }
}
