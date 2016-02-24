<?php namespace EuroMillions\web\migrations;

use Phinx\Migration\AbstractMigration;

class RenameRememberToken extends AbstractMigration
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
        $this->execute("ALTER TABLE users CHANGE token remember_token VARCHAR(255) DEFAULT NULL;");
    }
}
