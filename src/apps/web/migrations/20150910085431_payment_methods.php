<?php namespace EuroMillions\web\migrations;

use Phinx\Migration\AbstractMigration;

class PaymentMethods extends AbstractMigration
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
        $this->execute("CREATE TABLE payment_methods (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, payment_method_type VARCHAR(255) DEFAULT NULL, cvv INT DEFAULT NULL, card_number VARCHAR(255) DEFAULT NULL, card_holder_name VARCHAR(255) DEFAULT NULL, expiry_date VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_4FABF983A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
                        ALTER TABLE payment_methods ADD CONSTRAINT FK_4FABF983A76ED395 FOREIGN KEY (user_id) REFERENCES users (id);");
    }
}
