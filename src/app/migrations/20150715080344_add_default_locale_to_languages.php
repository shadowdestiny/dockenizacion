<?php

use Phinx\Migration\AbstractMigration;

class AddDefaultLocaleToLanguages extends AbstractMigration
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
        $this->execute("ALTER TABLE languages ADD defaultLocale VARCHAR(5) NOT NULL;");
    }
}
