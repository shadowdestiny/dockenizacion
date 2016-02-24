<?php

use Phinx\Migration\AbstractMigration;

class UserValidatedField extends AbstractMigration
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
        $this->execute("ALTER TABLE users ADD validated TINYINT(1) DEFAULT 0 NOT NULL;");
    }
}
