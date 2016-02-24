<?php namespace EuroMillions\web\migrations;
namespace EuroMillions\web\migrations;

use Phinx\Migration\AbstractMigration;

class InitialStructure extends AbstractMigration
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
        $this->execute("CREATE TABLE euromillions_draws (id INT UNSIGNED AUTO_INCREMENT NOT NULL, lottery_id INT UNSIGNED DEFAULT NULL, draw_date DATE NOT NULL, result_regular_numbers VARCHAR(255) DEFAULT NULL, result_lucky_numbers VARCHAR(255) DEFAULT NULL, jackpot_amount BIGINT NOT NULL, jackpot_currency_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_79652A5B38C98BF (draw_date), INDEX IDX_79652A5BCFAA77DD (lottery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;");

        $this->execute("CREATE TABLE languages (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ccode VARCHAR(6) NOT NULL, active TINYINT(1) DEFAULT '0' NOT NULL, UNIQUE INDEX UNIQ_A0D153794EE11504 (ccode), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;");

        $this->execute("CREATE TABLE lotteries (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT '0' NOT NULL, frequency VARCHAR(255) NOT NULL, jackpot_api VARCHAR(255) DEFAULT NULL, result_api VARCHAR(255) DEFAULT NULL, draw_time VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_DB65B0075E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;");

        $this->execute("CREATE TABLE translations (id INT UNSIGNED AUTO_INCREMENT NOT NULL, `key` VARCHAR(255) NOT NULL, used TINYINT(1) DEFAULT '0' NOT NULL, UNIQUE INDEX UNIQ_C6B7DA878A90ABA9 (`key`), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;");

        $this->execute("CREATE TABLE translation_details (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translation_id INT UNSIGNED DEFAULT NULL, language_id INT UNSIGNED DEFAULT NULL, value VARCHAR(255) NOT NULL, lang VARCHAR(6) NOT NULL, INDEX IDX_D32AF2789CAA2B25 (translation_id), INDEX IDX_D32AF27882F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;");

        $this->execute("ALTER TABLE euromillions_draws ADD CONSTRAINT FK_79652A5BCFAA77DD FOREIGN KEY (lottery_id) REFERENCES lotteries (id);");

        $this->execute("ALTER TABLE translation_details ADD CONSTRAINT FK_D32AF2789CAA2B25 FOREIGN KEY (translation_id) REFERENCES translations (id);");

        $this->execute("ALTER TABLE translation_details ADD CONSTRAINT FK_D32AF27882F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id);");
    }
}
