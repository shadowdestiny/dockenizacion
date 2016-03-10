<?php

use Phinx\Migration\AbstractMigration;

class InitialSchema extends AbstractMigration
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
        $this->execute('CREATE TABLE IF NOT EXISTS phinxlog (`version` bigint(20) NOT NULL,`migration_name` varchar(100) DEFAULT NULL,`start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,`end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY (`version`)) ENGINE=InnoDB DEFAULT CHARSET=utf8');
        $this->execute('DELETE FROM phinxlog;');
        $this->execute('DROP TABLE IF EXISTS users_notifications;');
        $this->execute('DROP TABLE IF EXISTS translation_details;');
        $this->execute('DROP TABLE IF EXISTS bets;');
        $this->execute('DROP TABLE IF EXISTS play_configs;');
        $this->execute('DROP TABLE IF EXISTS euromillions_draws;');
        $this->execute('DROP TABLE IF EXISTS users;');
        $this->execute('DROP TABLE IF EXISTS translations;');
        $this->execute('DROP TABLE IF EXISTS notifications;');
        $this->execute('DROP TABLE IF EXISTS lotteries;');
        $this->execute('DROP TABLE IF EXISTS languages;');
        $this->execute('DROP TABLE IF EXISTS site_config;');
        $this->execute('DROP TABLE IF EXISTS log_validation_api;');
        $this->execute('DROP TABLE IF EXISTS guest_users;');
        $this->execute('DROP TABLE IF EXISTS currencies;');

        $this->execute('CREATE TABLE bets (id INT UNSIGNED AUTO_INCREMENT NOT NULL, euromillions_draw_id INT UNSIGNED DEFAULT NULL, play_config_id INT UNSIGNED DEFAULT NULL, castillo_bet_id INT DEFAULT NULL, INDEX IDX_7C28752BC9AECF8 (euromillions_draw_id), INDEX IDX_7C28752B13C82ADE (play_config_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE currencies (code VARCHAR(3) NOT NULL, name VARCHAR(255) DEFAULT NULL, `order` INT DEFAULT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE euromillions_draws (id INT UNSIGNED AUTO_INCREMENT NOT NULL, lottery_id INT UNSIGNED DEFAULT NULL, draw_date DATE NOT NULL, result_regular_number_one INT DEFAULT NULL, result_regular_number_two INT DEFAULT NULL, result_regular_number_three INT DEFAULT NULL, result_regular_number_four INT DEFAULT NULL, result_regular_number_five INT DEFAULT NULL, result_lucky_number_one INT DEFAULT NULL, result_lucky_number_two INT DEFAULT NULL, jackpot_amount BIGINT DEFAULT NULL, jackpot_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_one_name VARCHAR(255) DEFAULT NULL, break_down_category_one_winners VARCHAR(255) DEFAULT NULL, break_down_category_one_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_one_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_two_name VARCHAR(255) DEFAULT NULL, break_down_category_two_winners VARCHAR(255) DEFAULT NULL, break_down_category_two_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_two_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_three_name VARCHAR(255) DEFAULT NULL, break_down_category_three_winners VARCHAR(255) DEFAULT NULL, break_down_category_three_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_three_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_four_name VARCHAR(255) DEFAULT NULL, break_down_category_four_winners VARCHAR(255) DEFAULT NULL, break_down_category_four_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_four_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_five_name VARCHAR(255) DEFAULT NULL, break_down_category_five_winners VARCHAR(255) DEFAULT NULL, break_down_category_five_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_five_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_six_name VARCHAR(255) DEFAULT NULL, break_down_category_six_winners VARCHAR(255) DEFAULT NULL, break_down_category_six_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_six_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_seven_name VARCHAR(255) DEFAULT NULL, break_down_category_seven_winners VARCHAR(255) DEFAULT NULL, break_down_category_seven_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_seven_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_eight_name VARCHAR(255) DEFAULT NULL, break_down_category_eight_winners VARCHAR(255) DEFAULT NULL, break_down_category_eight_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_eight_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_nine_name VARCHAR(255) DEFAULT NULL, break_down_category_nine_winners VARCHAR(255) DEFAULT NULL, break_down_category_nine_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_nine_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_ten_name VARCHAR(255) DEFAULT NULL, break_down_category_ten_winners VARCHAR(255) DEFAULT NULL, break_down_category_ten_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_ten_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_eleven_name VARCHAR(255) DEFAULT NULL, break_down_category_eleven_winners VARCHAR(255) DEFAULT NULL, break_down_category_eleven_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_eleven_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_twelve_name VARCHAR(255) DEFAULT NULL, break_down_category_twelve_winners VARCHAR(255) DEFAULT NULL, break_down_category_twelve_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_twelve_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_thirteen_name VARCHAR(255) DEFAULT NULL, break_down_category_thirteen_winners VARCHAR(255) DEFAULT NULL, break_down_category_thirteen_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_thirteen_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_79652A5B38C98BF (draw_date), INDEX IDX_79652A5BCFAA77DD (lottery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE guest_users (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE languages (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ccode VARCHAR(6) NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, defaultLocale VARCHAR(5) NOT NULL, UNIQUE INDEX UNIQ_A0D153794EE11504 (ccode), UNIQUE INDEX UNIQ_A0D1537925327B3C (defaultLocale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE log_validation_api (id INT UNSIGNED AUTO_INCREMENT NOT NULL, id_provider INT NOT NULL, status VARCHAR(255) NOT NULL, response LONGTEXT NOT NULL, received DATETIME NOT NULL, id_ticket INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE lotteries (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, frequency VARCHAR(255) NOT NULL, jackpot_api VARCHAR(255) DEFAULT NULL, result_api VARCHAR(255) DEFAULT NULL, draw_time VARCHAR(255) NOT NULL, single_bet_price_amount BIGINT DEFAULT NULL, single_bet_price_currency_name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_DB65B0075E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE notifications (id INT UNSIGNED AUTO_INCREMENT NOT NULL, description VARCHAR(255) DEFAULT NULL, notification_type INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE play_configs (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, start_draw_date DATE DEFAULT NULL, last_draw_date DATE DEFAULT NULL, frequency INT DEFAULT NULL, threshold_amount BIGINT DEFAULT NULL, threshold_currency_name VARCHAR(255) DEFAULT NULL, line_regular_number_one INT DEFAULT NULL, line_regular_number_two INT DEFAULT NULL, line_regular_number_three INT DEFAULT NULL, line_regular_number_four INT DEFAULT NULL, line_regular_number_five INT DEFAULT NULL, line_lucky_number_one INT DEFAULT NULL, line_lucky_number_two INT DEFAULT NULL, draw_days_config INT DEFAULT NULL, INDEX IDX_34771F83A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE site_config (id INT UNSIGNED AUTO_INCREMENT NOT NULL, fee_amount BIGINT DEFAULT NULL, fee_currency_name VARCHAR(255) DEFAULT NULL, fee_to_limit_amount BIGINT DEFAULT NULL, fee_to_limit_currency_name VARCHAR(255) DEFAULT NULL, default_currency_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE translations (id INT UNSIGNED AUTO_INCREMENT NOT NULL, `key` VARCHAR(255) NOT NULL, used TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_C6B7DA878A90ABA9 (`key`), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE translation_details (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translation_id INT UNSIGNED DEFAULT NULL, language_id INT UNSIGNED DEFAULT NULL, value VARCHAR(255) NOT NULL, lang VARCHAR(6) NOT NULL, INDEX IDX_D32AF2789CAA2B25 (translation_id), INDEX IDX_D32AF27882F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE users (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, validated TINYINT(1) DEFAULT \'0\' NOT NULL, street VARCHAR(255) DEFAULT NULL, zip INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, phone_number INT DEFAULT NULL, jackpot_reminder TINYINT(1) DEFAULT \'0\', show_modal_winning TINYINT(1) DEFAULT \'0\', password VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, remember_token VARCHAR(255) DEFAULT NULL, validation_token VARCHAR(255) DEFAULT NULL, wallet_uploaded_amount BIGINT DEFAULT NULL, wallet_uploaded_currency_name VARCHAR(255) DEFAULT NULL, wallet_winnings_amount BIGINT DEFAULT NULL, wallet_winnings_currency_name VARCHAR(255) DEFAULT NULL, user_currency_name VARCHAR(255) DEFAULT NULL, winning_above_amount BIGINT DEFAULT NULL, winning_above_currency_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('CREATE TABLE users_notifications (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id VARCHAR(255) DEFAULT NULL, notification_id INT UNSIGNED DEFAULT NULL, active TINYINT(1) DEFAULT \'1\', type_config_value VARCHAR(255) DEFAULT NULL, INDEX IDX_69E5B8DEA76ED395 (user_id), INDEX IDX_69E5B8DEEF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
        $this->execute('ALTER TABLE bets ADD CONSTRAINT FK_7C28752BC9AECF8 FOREIGN KEY (euromillions_draw_id) REFERENCES euromillions_draws (id);');
        $this->execute('ALTER TABLE bets ADD CONSTRAINT FK_7C28752B13C82ADE FOREIGN KEY (play_config_id) REFERENCES play_configs (id);');
        $this->execute('ALTER TABLE euromillions_draws ADD CONSTRAINT FK_79652A5BCFAA77DD FOREIGN KEY (lottery_id) REFERENCES lotteries (id);');
        $this->execute('ALTER TABLE play_configs ADD CONSTRAINT FK_34771F83A76ED395 FOREIGN KEY (user_id) REFERENCES users (id);');
        $this->execute('ALTER TABLE translation_details ADD CONSTRAINT FK_D32AF2789CAA2B25 FOREIGN KEY (translation_id) REFERENCES translations (id);');
        $this->execute('ALTER TABLE translation_details ADD CONSTRAINT FK_D32AF27882F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id);');
        $this->execute('ALTER TABLE users_notifications ADD CONSTRAINT FK_69E5B8DEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);');
        $this->execute('ALTER TABLE users_notifications ADD CONSTRAINT FK_69E5B8DEEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notifications (id);');
    }
}
