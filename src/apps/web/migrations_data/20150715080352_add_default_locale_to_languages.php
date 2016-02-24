<?php namespace EuroMillions\web\migrations_data;

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
        $this->execute("UPDATE languages SET defaultLocale = 'en_US' WHERE ccode = 'en';");
        $this->execute("UPDATE languages SET defaultLocale = 'es_ES' WHERE ccode = 'es';");
    }
}
