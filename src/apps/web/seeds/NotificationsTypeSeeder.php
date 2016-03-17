<?php

use Phinx\Seed\AbstractSeed;

class NotificationsTypeSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'description' => 'When Jackpot reach',
                'notification_type' => 1
            ],
            [
                'description' => 'When Auto-Play has not enough funds',
                'notification_type' => 2
            ],
            [
                'description' => 'When Auto-Play has played the last Draw',
                'notification_type' => 3
            ],
            [
                'description' => 'Results of the Draw',
                'notification_type' => 4
            ],
        ];
        $notificationsType = $this->table('notifications_type');
        $notificationsType->insert($data)
            ->save();
    }
}
