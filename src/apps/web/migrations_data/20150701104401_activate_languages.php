<?php

use Phinx\Migration\AbstractMigration;

class ActivateLanguages extends AbstractMigration
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
        $this->execute("UPDATE languages SET active = 1 WHERE ccode = 'en' OR ccode = 'es'");
    }
}
