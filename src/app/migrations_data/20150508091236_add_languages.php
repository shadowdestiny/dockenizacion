<?php

use Phinx\Migration\AbstractMigration;

class AddLanguages extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $sql = "INSERT INTO languages VALUES (1, 'en'), (2, 'es'), (3, 'fr'), (4, 'de'), (5, 'sw'), (6, 'nl')";
        $this->execute($sql);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $sql = "DELETE FROM languages WHERE id BETWEEN 1 AND 6";
        $this->execute($sql);
    }
}