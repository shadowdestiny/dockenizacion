<?php

use Phinx\Migration\AbstractMigration;

class UserTest extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $sql = 'INSERT INTO `euromillions`.`users` (`id`,`email`, `password`, `remember_token`, `balance_amount`, `balance_currency_name`, `name`, `surname`, `country`, `validated`, `validation_token`, `street`, `zip`, `city`, `phone_number`, `user_currency_name`) VALUES
              ("832063cb-a559-11e5-b358-0242ac110002","alessio.carone@panamedia.net", "test01", "3c44633d83a5780f5bac7dcc6eccb0ab", "0", "EUR", "Test", "test01", "233", "0", "3c44633d83a5780f5bac7dcc6eccb0ab", "", "", "", "", "EUR");';
        $this->execute($sql);
    }
}
