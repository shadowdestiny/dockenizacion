<?php namespace EuroMillions\web\migrations;

use Phinx\Migration\AbstractMigration;

class IndividualNumbers extends AbstractMigration
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
            $this->execute('ALTER TABLE play_configs ADD line_regular_number_one INT DEFAULT NULL, ADD line_regular_number_two INT DEFAULT NULL, ADD line_regular_number_three INT DEFAULT NULL, ADD line_regular_number_four INT DEFAULT NULL, ADD line_regular_number_five INT DEFAULT NULL, ADD line_lucky_number_one INT DEFAULT NULL, ADD line_lucky_number_two INT DEFAULT NULL;
                            ALTER TABLE euromillions_draws ADD result_regular_number_one INT DEFAULT NULL, ADD result_regular_number_two INT DEFAULT NULL, ADD result_regular_number_three INT DEFAULT NULL, ADD result_regular_number_four INT DEFAULT NULL, ADD result_regular_number_five INT DEFAULT NULL, ADD result_lucky_number_one INT DEFAULT NULL, ADD result_lucky_number_two INT DEFAULT NULL;
              ');
    }
}
