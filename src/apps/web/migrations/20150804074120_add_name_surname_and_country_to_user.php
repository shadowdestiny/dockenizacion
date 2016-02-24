<?php

use Phinx\Migration\AbstractMigration;

class AddNameSurnameAndCountryToUser extends AbstractMigration
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
        $this->execute("ALTER TABLE users ADD name VARCHAR(255) NOT NULL, ADD surname VARCHAR(255) NOT NULL, ADD country VARCHAR(255) NOT NULL;");
    }
}
