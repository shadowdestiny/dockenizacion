<?php

use Phinx\Migration\AbstractMigration;

class JackpotAsValueObject extends AbstractMigration
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
        $this->execute("ALTER TABLE euromillions_draws ADD jackpot_currency_name VARCHAR(255) NOT NULL, CHANGE jackpot jackpot_amount BIGINT NOT NULL;");
    }
}
