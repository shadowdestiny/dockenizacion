<?php

use Phinx\Migration\AbstractMigration;

class RemoveUsernameFieldAndCreateGuestUsers extends AbstractMigration
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
        $this->execute("CREATE TABLE guest_users (id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;");
        $this->execute("CREATE UNIQUE INDEX UNIQ_A0D1537925327B3C ON languages (defaultLocale);");
        $this->execute("ALTER TABLE users DROP username;");
    }
}
