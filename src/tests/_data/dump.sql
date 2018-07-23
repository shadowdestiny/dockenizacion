CREATE TABLE users (id CHAR(36) NOT NULL COMMENT '(DC2Type:uuid)', name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, validated TINYINT(1) DEFAULT '0' NOT NULL, street VARCHAR(255) DEFAULT NULL, zip INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, jackpot_reminder TINYINT(1) DEFAULT '0', show_modal_winning TINYINT(1) DEFAULT '0', bank_user_name VARCHAR(255) DEFAULT NULL, bank_surname VARCHAR(255) DEFAULT NULL, bank_name VARCHAR(255) DEFAULT NULL, bank_account VARCHAR(255) DEFAULT NULL, bank_swift VARCHAR(255) DEFAULT NULL, created DATETIME DEFAULT NULL, last_connection DATETIME DEFAULT NULL, default_language VARCHAR(255) DEFAULT NULL, disabled_date DATETIME DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, remember_token VARCHAR(255) DEFAULT NULL, validation_token VARCHAR(255) DEFAULT NULL, wallet_uploaded_amount BIGINT DEFAULT NULL, wallet_uploaded_currency_name VARCHAR(255) DEFAULT NULL, wallet_winnings_amount BIGINT DEFAULT NULL, wallet_winnings_currency_name VARCHAR(255) DEFAULT NULL, wallet_subscription_amount BIGINT DEFAULT NULL, wallet_subscription_currency_name VARCHAR(255) DEFAULT NULL, user_currency_name VARCHAR(255) DEFAULT NULL, winning_above_amount BIGINT DEFAULT NULL, winning_above_currency_name VARCHAR(255) DEFAULT NULL, ip_address_value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE tc_actions (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(45) DEFAULT NULL, conditions LONGTEXT DEFAULT NULL, trackingCode_id INT UNSIGNED DEFAULT NULL, INDEX IDX_6DE43F0EB9D8A344 (trackingCode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE matcher (id INT UNSIGNED AUTO_INCREMENT NOT NULL, matchSide VARCHAR(1) DEFAULT NULL, drawDate DATE NOT NULL, matchStatus VARCHAR(10) DEFAULT NULL, matchID BIGINT DEFAULT NULL, matchTypeID INT DEFAULT NULL, matchDate DATETIME DEFAULT NULL, providerBetId BIGINT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, raffle_million LONGTEXT DEFAULT NULL, raffle_rain LONGTEXT DEFAULT NULL, prize_amount BIGINT DEFAULT NULL, prize_currency_name VARCHAR(255) DEFAULT NULL, userId CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)', INDEX IDX_7121252C64B64DCC (userId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE site_config (id INT UNSIGNED AUTO_INCREMENT NOT NULL, fee_amount BIGINT DEFAULT NULL, fee_currency_name VARCHAR(255) DEFAULT NULL, fee_to_limit_amount BIGINT DEFAULT NULL, fee_to_limit_currency_name VARCHAR(255) DEFAULT NULL, default_currency_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE lotteries (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT '0' NOT NULL, frequency VARCHAR(255) NOT NULL, jackpot_api VARCHAR(255) DEFAULT NULL, result_api VARCHAR(255) DEFAULT NULL, draw_time VARCHAR(255) NOT NULL, single_bet_price_amount BIGINT DEFAULT NULL, single_bet_price_currency_name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_DB65B0075E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE currencies (code VARCHAR(3) NOT NULL, name VARCHAR(255) DEFAULT NULL, `order` INT DEFAULT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE translations (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translationKey VARCHAR(255) NOT NULL, used TINYINT(1) DEFAULT '0' NOT NULL, description VARCHAR(100) DEFAULT NULL, translationCategory_id INT UNSIGNED DEFAULT NULL, UNIQUE INDEX UNIQ_C6B7DA874836035C (translationKey), INDEX IDX_C6B7DA878D19538D (translationCategory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE euromillions_draws (id INT UNSIGNED AUTO_INCREMENT NOT NULL, lottery_id INT UNSIGNED DEFAULT NULL, draw_date DATE NOT NULL, result_regular_number_one INT DEFAULT NULL, result_regular_number_two INT DEFAULT NULL, result_regular_number_three INT DEFAULT NULL, result_regular_number_four INT DEFAULT NULL, result_regular_number_five INT DEFAULT NULL, result_lucky_number_one INT DEFAULT NULL, result_lucky_number_two INT DEFAULT NULL, jackpot_amount BIGINT DEFAULT NULL, jackpot_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_one_name VARCHAR(255) DEFAULT NULL, break_down_category_one_winners VARCHAR(255) DEFAULT NULL, break_down_category_one_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_one_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_two_name VARCHAR(255) DEFAULT NULL, break_down_category_two_winners VARCHAR(255) DEFAULT NULL, break_down_category_two_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_two_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_three_name VARCHAR(255) DEFAULT NULL, break_down_category_three_winners VARCHAR(255) DEFAULT NULL, break_down_category_three_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_three_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_four_name VARCHAR(255) DEFAULT NULL, break_down_category_four_winners VARCHAR(255) DEFAULT NULL, break_down_category_four_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_four_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_five_name VARCHAR(255) DEFAULT NULL, break_down_category_five_winners VARCHAR(255) DEFAULT NULL, break_down_category_five_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_five_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_six_name VARCHAR(255) DEFAULT NULL, break_down_category_six_winners VARCHAR(255) DEFAULT NULL, break_down_category_six_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_six_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_seven_name VARCHAR(255) DEFAULT NULL, break_down_category_seven_winners VARCHAR(255) DEFAULT NULL, break_down_category_seven_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_seven_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_eight_name VARCHAR(255) DEFAULT NULL, break_down_category_eight_winners VARCHAR(255) DEFAULT NULL, break_down_category_eight_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_eight_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_nine_name VARCHAR(255) DEFAULT NULL, break_down_category_nine_winners VARCHAR(255) DEFAULT NULL, break_down_category_nine_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_nine_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_ten_name VARCHAR(255) DEFAULT NULL, break_down_category_ten_winners VARCHAR(255) DEFAULT NULL, break_down_category_ten_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_ten_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_eleven_name VARCHAR(255) DEFAULT NULL, break_down_category_eleven_winners VARCHAR(255) DEFAULT NULL, break_down_category_eleven_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_eleven_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_twelve_name VARCHAR(255) DEFAULT NULL, break_down_category_twelve_winners VARCHAR(255) DEFAULT NULL, break_down_category_twelve_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_twelve_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_thirteen_name VARCHAR(255) DEFAULT NULL, break_down_category_thirteen_winners VARCHAR(255) DEFAULT NULL, break_down_category_thirteen_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_thirteen_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_fourteen_name VARCHAR(255) DEFAULT NULL, break_down_category_fourteen_winners VARCHAR(255) DEFAULT NULL, break_down_category_fourteen_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_fourteen_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_fifteen_name VARCHAR(255) DEFAULT NULL, break_down_category_fifteen_winners VARCHAR(255) DEFAULT NULL, break_down_category_fifteen_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_fifteen_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_sixteen_name VARCHAR(255) DEFAULT NULL, break_down_category_sixteen_winners VARCHAR(255) DEFAULT NULL, break_down_category_sixteen_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_sixteen_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, break_down_category_seventeen_name VARCHAR(255) DEFAULT NULL, break_down_category_seventeen_winners VARCHAR(255) DEFAULT NULL, break_down_category_seventeen_lottery_prize_amount BIGINT DEFAULT NULL, break_down_category_seventeen_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, raffle_value VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_79652A5B38C98BF (draw_date), INDEX IDX_79652A5BCFAA77DD (lottery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE matchHistory (id INT UNSIGNED AUTO_INCREMENT NOT NULL, userID VARCHAR(36) DEFAULT NULL, matchTypeID INT DEFAULT NULL, providerBetId BIGINT DEFAULT NULL, drawDate DATE NOT NULL, matchStatus VARCHAR(10) DEFAULT NULL, matchDate DATETIME DEFAULT NULL, lPrize_amount BIGINT DEFAULT NULL, lPrize_currency_name VARCHAR(255) DEFAULT NULL, rPrize_amount BIGINT DEFAULT NULL, rPrize_currency_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE users_api (id CHAR(36) NOT NULL COMMENT '(DC2Type:uuid)', username VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE translation_details (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translation_id INT UNSIGNED DEFAULT NULL, language_id INT UNSIGNED DEFAULT NULL, value LONGTEXT NOT NULL, lang VARCHAR(6) NOT NULL, INDEX IDX_D32AF2789CAA2B25 (translation_id), INDEX IDX_D32AF27882F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE trackingCodes (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(45) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE notifications_type (id INT UNSIGNED AUTO_INCREMENT NOT NULL, description VARCHAR(255) DEFAULT NULL, notification_type INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE user_notifications (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)', notification_id INT UNSIGNED DEFAULT NULL, active TINYINT(1) DEFAULT '1', type_config_value VARCHAR(255) DEFAULT NULL, INDEX IDX_8E8E1D83A76ED395 (user_id), INDEX IDX_8E8E1D83EF1A9D84 (notification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE tc_users_list (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)', trackingCode_id INT UNSIGNED DEFAULT NULL, INDEX IDX_A97819EAB9D8A344 (trackingCode_id), INDEX IDX_A97819EAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE bets (id INT UNSIGNED AUTO_INCREMENT NOT NULL, euromillions_draw_id INT UNSIGNED DEFAULT NULL, match_numbers VARCHAR(255) DEFAULT NULL, match_stars VARCHAR(255) DEFAULT NULL, prize_amount BIGINT DEFAULT NULL, prize_currency_name VARCHAR(255) DEFAULT NULL, castillo_bet_id INT DEFAULT NULL, playConfig_id INT UNSIGNED DEFAULT NULL, INDEX IDX_7C28752BC9AECF8 (euromillions_draw_id), INDEX IDX_7C28752B43FD6CAB (playConfig_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE playconfig_transaction (id INT UNSIGNED AUTO_INCREMENT NOT NULL, transactionID INT UNSIGNED DEFAULT NULL, playConfig_id INT UNSIGNED DEFAULT NULL, INDEX IDX_3CBA9E0AF99A11DC (transactionID), INDEX IDX_3CBA9E0A43FD6CAB (playConfig_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE log_validation_api (id INT UNSIGNED AUTO_INCREMENT NOT NULL, bet_id INT UNSIGNED DEFAULT NULL, id_provider INT NOT NULL, status VARCHAR(255) NOT NULL, response LONGTEXT NOT NULL, received DATETIME NOT NULL, id_ticket BIGINT NOT NULL, uuid VARCHAR(255) NOT NULL, INDEX IDX_4B44F776D871DC26 (bet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE tc_attributes (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(45) DEFAULT NULL, conditions LONGTEXT DEFAULT NULL, function_name VARCHAR(45) DEFAULT NULL, relationship_table VARCHAR(45) DEFAULT NULL, trackingCode_id INT UNSIGNED DEFAULT NULL, INDEX IDX_13A2318CB9D8A344 (trackingCode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE users_admin (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, surname VARCHAR(30) NOT NULL, email VARCHAR(60) NOT NULL, password VARCHAR(32) NOT NULL, useraccess VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE christmas_awards (id INT UNSIGNED AUTO_INCREMENT NOT NULL, number VARCHAR(5) NOT NULL, christmas_ticket_id INT NOT NULL, prize INT NOT NULL, UNIQUE INDEX UNIQ_E2A6793896901F54 (number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE christmas_tickets (id INT UNSIGNED AUTO_INCREMENT NOT NULL, number VARCHAR(5) NOT NULL, n_series INT NOT NULL, serie_init INT NOT NULL, serie_end INT NOT NULL, n_fractions INT NOT NULL, fraction_init INT NOT NULL, fraction_end INT NOT NULL, UNIQUE INDEX UNIQ_2686B6EB96901F54 (number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE matchType (id INT UNSIGNED AUTO_INCREMENT NOT NULL, matchName VARCHAR(20) DEFAULT NULL, lottery VARCHAR(10) DEFAULT NULL, transactionType VARCHAR(8) DEFAULT NULL, leftEdge VARCHAR(10) DEFAULT NULL, rightEdge VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE blog (id INT UNSIGNED AUTO_INCREMENT NOT NULL, url VARCHAR(100) NOT NULL, title VARCHAR(100) NOT NULL, title_tag VARCHAR(100) NOT NULL, description VARCHAR(200) NOT NULL, description_tag VARCHAR(200) NOT NULL, canonical VARCHAR(200) NOT NULL, language VARCHAR(2) NOT NULL, published TINYINT(1) NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(100) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE translation_categories (id INT UNSIGNED AUTO_INCREMENT NOT NULL, category_name VARCHAR(255) DEFAULT NULL, category_code VARCHAR(10) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE play_configs (id INT UNSIGNED AUTO_INCREMENT NOT NULL, lottery_id INT UNSIGNED DEFAULT NULL, user_id CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)', active TINYINT(1) DEFAULT '1' NOT NULL, start_draw_date DATE DEFAULT NULL, last_draw_date DATE DEFAULT NULL, frequency INT DEFAULT NULL, power_play INT DEFAULT NULL, line_regular_number_one INT DEFAULT NULL, line_regular_number_two INT DEFAULT NULL, line_regular_number_three INT DEFAULT NULL, line_regular_number_four INT DEFAULT NULL, line_regular_number_five INT DEFAULT NULL, line_lucky_number_one INT DEFAULT NULL, line_lucky_number_two INT DEFAULT NULL, discount_value DOUBLE PRECISION DEFAULT NULL, INDEX IDX_34771F83CFAA77DD (lottery_id), INDEX IDX_34771F83A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE transactions (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id CHAR(36) DEFAULT NULL COMMENT '(DC2Type:uuid)', date DATETIME DEFAULT NULL, transactionID VARCHAR(255) DEFAULT NULL, message VARCHAR(255) DEFAULT NULL, wallet_before_uploaded_amount BIGINT DEFAULT NULL, wallet_before_uploaded_currency_name VARCHAR(255) DEFAULT NULL, wallet_before_winnings_amount BIGINT DEFAULT NULL, wallet_before_winnings_currency_name VARCHAR(255) DEFAULT NULL, wallet_before_subscription_amount BIGINT DEFAULT NULL, wallet_before_subscription_currency_name VARCHAR(255) DEFAULT NULL, wallet_after_uploaded_amount BIGINT DEFAULT NULL, wallet_after_uploaded_currency_name VARCHAR(255) DEFAULT NULL, wallet_after_winnings_amount BIGINT DEFAULT NULL, wallet_after_winnings_currency_name VARCHAR(255) DEFAULT NULL, wallet_after_subscription_amount BIGINT DEFAULT NULL, wallet_after_subscription_currency_name VARCHAR(255) DEFAULT NULL, entity_type VARCHAR(255) NOT NULL, data VARCHAR(255) DEFAULT NULL, INDEX IDX_EAA81A4CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE languages (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ccode VARCHAR(6) NOT NULL, active TINYINT(1) DEFAULT '0' NOT NULL, defaultLocale VARCHAR(5) NOT NULL, UNIQUE INDEX UNIQ_A0D153794EE11504 (ccode), UNIQUE INDEX UNIQ_A0D1537925327B3C (defaultLocale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE articles (id INT UNSIGNED AUTO_INCREMENT NOT NULL, content LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE tc_actions ADD CONSTRAINT FK_6DE43F0EB9D8A344 FOREIGN KEY (trackingCode_id) REFERENCES trackingCodes (id);
ALTER TABLE matcher ADD CONSTRAINT FK_7121252C64B64DCC FOREIGN KEY (userId) REFERENCES users (id);
ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA878D19538D FOREIGN KEY (translationCategory_id) REFERENCES translation_categories (id);
ALTER TABLE euromillions_draws ADD CONSTRAINT FK_79652A5BCFAA77DD FOREIGN KEY (lottery_id) REFERENCES lotteries (id);
ALTER TABLE translation_details ADD CONSTRAINT FK_D32AF2789CAA2B25 FOREIGN KEY (translation_id) REFERENCES translations (id);
ALTER TABLE translation_details ADD CONSTRAINT FK_D32AF27882F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id);
ALTER TABLE user_notifications ADD CONSTRAINT FK_8E8E1D83A76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
ALTER TABLE user_notifications ADD CONSTRAINT FK_8E8E1D83EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notifications_type (id);
ALTER TABLE tc_users_list ADD CONSTRAINT FK_A97819EAB9D8A344 FOREIGN KEY (trackingCode_id) REFERENCES trackingCodes (id);
ALTER TABLE tc_users_list ADD CONSTRAINT FK_A97819EAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
ALTER TABLE bets ADD CONSTRAINT FK_7C28752BC9AECF8 FOREIGN KEY (euromillions_draw_id) REFERENCES euromillions_draws (id);
ALTER TABLE bets ADD CONSTRAINT FK_7C28752B43FD6CAB FOREIGN KEY (playConfig_id) REFERENCES play_configs (id);
ALTER TABLE playconfig_transaction ADD CONSTRAINT FK_3CBA9E0AF99A11DC FOREIGN KEY (transactionID) REFERENCES transactions (id);
ALTER TABLE playconfig_transaction ADD CONSTRAINT FK_3CBA9E0A43FD6CAB FOREIGN KEY (playConfig_id) REFERENCES play_configs (id);
ALTER TABLE log_validation_api ADD CONSTRAINT FK_4B44F776D871DC26 FOREIGN KEY (bet_id) REFERENCES bets (id);
ALTER TABLE tc_attributes ADD CONSTRAINT FK_13A2318CB9D8A344 FOREIGN KEY (trackingCode_id) REFERENCES trackingCodes (id);
ALTER TABLE play_configs ADD CONSTRAINT FK_34771F83CFAA77DD FOREIGN KEY (lottery_id) REFERENCES lotteries (id);
ALTER TABLE play_configs ADD CONSTRAINT FK_34771F83A76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
