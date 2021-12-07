<?php


use Phinx\Seed\AbstractSeed;

class RoleSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $names = ['ADMIN', 'SELLER', 'BUYER'];
        $data = [];
        foreach($names as $name) {
            $data[] = [
                'name'      => $name
            ];
        }

        $this->insert('roles', $data);
    }
}
